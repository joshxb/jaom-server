<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DonateTransactions;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonateTransactionsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 20); // Change 'page' to 'per_page'
        $donate = DonateTransactions::paginate($perPage);

        return response()->json($donate);
    }

    public function show($id)
    {
        try {
            // Try to find the Donate model with the given ID.
            $donate = DonateTransactions::findOrFail($id);
            return response()->json($donate);
        } catch (ModelNotFoundException $exception) {
            // If the model is not found, throw an error response.
            return response()->json(['error' => 'Donate not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullname' => 'required|string',
            //Philippines phone number
            'phone' => 'required|string|max:20|min:11',
            'email' => 'required|email',
            'location' => 'required|string',
            'payment_method' => 'required',
            'amount' => 'required|integer|min:1',
            'screenshot_img' => 'required|image',
        ]);

        $image = $request->file('screenshot_img');

        // Read the contents of the file and convert it to a blob
        $ss = file_get_contents($image->getPathname());
        $ss = base64_encode($ss);

        // Create a new donation transaction record to the database
        DonateTransactions::create([
            "user_id" => $user->id,
            "fullname" => $request->fullname,
            "phone" => $request->phone,
            "email" => $request->email,
            "location" => $request->location,
            "payment_method" => $request->payment_method,
            "amount" => $request->amount,
            "screenshot_img" => $ss,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Donation successfully recorded! Thank you for your donation to our ministry! 😊',
        ], 200, ['Content-Type' => 'application/json; charset=utf-8']);
    }

    public function update(Request $request, $id)
    {
        $transac = DonateTransactions::find($id);

        // Check if the authenticated user is authorized to update the profile
        if (Auth::user()->id !== $transac->user_id) {
            return response()->json(['message' => 'Permission denied'], 401);
        }

        $requestData = $request->all();

        $transac->update($requestData);

        return response()->json([
            'data' => $transac,
            'message' => 'Data updated successfully!',
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        try {
            $donate = DonateTransactions::where("id", $id)->where("user_id", $user->id)->firstOrFail();

            $donate->delete();

            return response()->json(['message' => 'Donation Transaction deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Donate Transaction not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while processing your request'], 500);
        }
    }

    public function destroyAll()
    {
        $user = Auth::user();

        try {
            $donate = DonateTransactions::where("user_id", $user->id)->get();

            $donate->each->delete();

            return response()->json(['message' => "All Donation's Transactions deleted successfully"]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Donation\'s Transaction not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while processing your request'], 500);
        }
    }

}
