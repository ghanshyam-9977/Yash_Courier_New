<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
class UnitController extends Controller
{
    // Show the form to create a new unit
    public function create_unit()
    {
        return view('backend.Unit.create');
    }

    // Store a newly created unit in the database
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'transport_type' => 'required|in:By Road,By Air',
            'weight' => 'nullable|numeric',
            'weight_price' => 'nullable|numeric',
        ]);

        // Create a new unit in the database
        Unit::create([
            'name' => $validated['name'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'transport_type' => $validated['transport_type'],
            'weight' => $validated['weight'] ?? 0,
            'weight_price' => $validated['weight_price'] ?? 0,
        ]);
        Toastr::success(__('hubs.added_msg_unit'),__('message.success'));
        return redirect()->route('unit.index')->with('success', 'Unit created successfully!');
    }

    // Show all units (for listing purposes)
    public function indexunit()
    {
        $units = Unit::latest()->get(); // Fetch all units
        return view('backend.Unit.units', compact('units'));
    }


    // Show the form to edit an existing unit
    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('backend.Unit.edit', compact('unit'));
    }

    // Update an existing unit in the database
    // public function update(Request $request, Unit $id)
    // {
    //     // Validate the incoming data
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'quantity' => 'required|integer',
    //         'price' => 'required|numeric',
    //         'transport_type' => 'required|in:By Road,By Air',
    //         'weight' => 'nullable|numeric',
    //         'weight_price' => 'nullable|numeric',
    //     ]);

    //     $unit = Unit::findOrFail($id);
    //     $unit->update([
    //         'name' => $validated['name'],
    //         'quantity' => $validated['quantity'],
    //         'price' => $validated['price'],
    //         'transport_type' => $validated['transport_type'],
    //         'weight' => $validated['weight'] ?? 0,
    //         'weight_price' => $validated['weight_price'] ?? 0,
    //     ]);

    //     return redirect()->route('unit.index')->with('success', 'Unit updated successfully!');
    // }

    // Delete an existing unit from the database
    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
 Toastr::success(__('hubs.added_msg_unit'),__('message.success'));
        // return redirect()->route('unit.index')->with('success', 'Unit created successfully!');
        return redirect()->route('unit.index')->with('success', 'Unit deleted successfully!');
    }



    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'transport_type' => 'required|string',
        ]);

        $unit->update([
                    'name' => $validated['name'],
                    'quantity' => $validated['quantity'],
                    'price' => $validated['price'],
                    'transport_type' => $validated['transport_type'],
                    // 'weight' => $validated['weight'] ?? 0,
                    // 'weight_price' => $validated['weight_price'] ?? 0,
                ]);

        return redirect()->route('unit.index')->with('success', 'Unit updated successfully');
    }
}
