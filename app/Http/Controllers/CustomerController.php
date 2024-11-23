<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use App\Imports\CustomersImport;
use App\Models\Address;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('addresses')->paginate(5);
        return view('customers.index', compact('customers'));
    }

    // public function show($id)
    // {
    //     dd('fsd');
    //     return redirect()->route('customers.index');
    // }
    public function create()
    {
        return view('customers.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:15',
        ]);

        Customer::create($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
    }

    public function edit(Customer $customer)
    {
        return view('customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:15',
        ]);

        $customer->update($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:10240',
        ], [
            'file.max' => 'The file size should not exceed 10 MB.',
        ]);

        try {
            Excel::import(new CustomersImport, $request->file('file'));
            return back()->with('success', 'Customers imported successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to import. Error: ' . $e->getMessage());
        }
    }

    public function exportExcel()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }

    public function showaddressForm()
    {
        $customers = Customer::all();
        return view('customers.address', compact('customers'));
    }
    public function storeaddress(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
        ]);

        Address::create([
            'customer_id' => $request->customer_id,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
        ]);

        return redirect()->route('customers.index')->with('success', 'Address saved successfully!');
    }
}
