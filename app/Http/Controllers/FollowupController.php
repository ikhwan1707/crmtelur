<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Followup;
use App\Services\RepeatOrderService;
use Illuminate\Http\Request;

class FollowupController extends Controller
{
    public function index()
    {
        $followups = Followup::with('customer', 'user')->latest()->paginate(15);
        return view('followups.index', compact('followups'));
    }
    public function create(Customer $customer = null)
    {
        return view('followups.create', [
            'customers' => Customer::orderBy('nama')->get(),
            'selected' => $customer,
        ]);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'tgl_followup' => 'required|date',
            'jenis' => 'required|in:whatsapp,telepon,kunjungan',
            'catatan' => 'nullable|string',
            'hasil' => 'required|in:order,tunda,tidak_aktif',
        ]);
        $data['user_id'] = auth()->id();
        Followup::create($data);

        // Jika hasil 'tidak_aktif', set pelanggan jadi pasif & repeat order berhenti
        if ($data['hasil'] === 'tidak_aktif') {
            $customer = Customer::find($data['customer_id']);
            $customer->update(['status' => 'pasif']);
            $customer->repeatOrders()->update(['status' => 'berhenti']);
        }

        return redirect()->route('followups.index')->with('success', 'Follow-up disimpan.');
    }
    public function destroy(Followup $followup)
    {
        $followup->delete();
        return back()->with('success', 'Follow-up dihapus.');
    }
}