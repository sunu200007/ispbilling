<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('pelanggan');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->whereHas('pelanggan', function($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%')
                  ->orWhere('username', 'like', '%'.$request->search.'%');
            })->orWhere('no_invoice', 'like', '%'.$request->search.'%');
        }

        $invoice = $query->latest()->paginate(20);
        return view('admin.invoice.index', compact('invoice'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::where('status', 'aktif')->get();
        return view('admin.invoice.create', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id'        => 'required|exists:pelanggan,id',
            'jumlah'              => 'required|integer|min:0',
            'tanggal_invoice'     => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date',
        ]);

        Invoice::create([
            'no_invoice'          => $this->generateNoInvoice(),
            'pelanggan_id'        => $request->pelanggan_id,
            'jumlah'              => $request->jumlah,
            'tanggal_invoice'     => $request->tanggal_invoice,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'status'              => 'unpaid',
        ]);

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dibuat.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('pelanggan.paket');
        return view('admin.invoice.show', compact('invoice'));
    }

    public function bayar(Request $request, Invoice $invoice)
    {
        $request->validate([
            'metode_bayar' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            $invoice->update([
                'status'       => 'paid',
                'metode_bayar' => $request->metode_bayar,
                'dibayar_at'   => now(),
            ]);

            // Perpanjang expiration 1 bulan
            $pelanggan = $invoice->pelanggan;
            $newExpiration = \Carbon\Carbon::parse($pelanggan->tanggal_jatuh_tempo)
                ->addMonth()
                ->format('Y-m-d');

            $pelanggan->update(['tanggal_jatuh_tempo' => $newExpiration]);

            // Sync ke RADIUS
            $expiration = \Carbon\Carbon::parse($newExpiration)->format('d M Y H:i:s');
            $exists = DB::connection('radius')->table('radcheck')
                ->where('username', $pelanggan->username)
                ->where('attribute', 'Expiration')
                ->exists();

            if ($exists) {
                DB::connection('radius')->table('radcheck')
                    ->where('username', $pelanggan->username)
                    ->where('attribute', 'Expiration')
                    ->update(['value' => $expiration]);
            } else {
                DB::connection('radius')->table('radcheck')->insert([
                    ['username' => $pelanggan->username, 'attribute' => 'Expiration', 'op' => ':=', 'value' => $expiration],
                ]);
            }
        });

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dibayar dan expiration diperpanjang 1 bulan.');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Invoice yang sudah dibayar tidak bisa dihapus.');
        }
        $invoice->delete();
        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dihapus.');
    }

    public function generateBulk()
    {
        $bulan = Carbon::now()->format('Y-m');
        $pelanggan = Pelanggan::with('paket')->where('status', 'aktif')->get();
        $generated = 0;
        $skipped   = 0;

        foreach ($pelanggan as $p) {
            // Cek apakah invoice bulan ini sudah ada
            $exists = Invoice::where('pelanggan_id', $p->id)
                ->where('tanggal_invoice', 'like', $bulan.'%')
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Invoice::create([
                'no_invoice'          => $this->generateNoInvoice(),
                'pelanggan_id'        => $p->id,
                'jumlah'              => $p->paket->harga,
                'tanggal_invoice'     => Carbon::now()->startOfMonth(),
                'tanggal_jatuh_tempo' => Carbon::now()->endOfMonth(),
                'status'              => 'unpaid',
            ]);
            $generated++;
        }

        return redirect()->route('invoice.index')
            ->with('success', "Generate selesai: {$generated} invoice dibuat, {$skipped} dilewati.");
    }

    public function updateOverdue()
    {
        $updated = Invoice::where('status', 'unpaid')
            ->where('tanggal_jatuh_tempo', '<', Carbon::today())
            ->update(['status' => 'overdue']);

        return redirect()->route('invoice.index')
            ->with('success', "{$updated} invoice diupdate menjadi overdue.");
    }

    private function generateNoInvoice(): string
    {
        $prefix = 'INV-'.Carbon::now()->format('Ym').'-';
        $last   = Invoice::where('no_invoice', 'like', $prefix.'%')
            ->orderBy('no_invoice', 'desc')
            ->first();
        $number = $last ? (int) substr($last->no_invoice, -4) + 1 : 1;
        return $prefix.str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
