<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Cart;
class CartController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->query('user_id');
    
        
        $cart = Cart::where('user_id', $user_id)->get();
    
        if ($cart->isNotEmpty()) {
            return response()->json([
                'cart' => $cart,
            ]);
        } else {
            return response()->json(['msg' => "There's no data in the cart"]);
        }
    }
    public function increaseQuantity (Request $request) {
        $item = Cart::where('user_id', $request->user_id)->where('id', $request->id)->first();
        $item->quantity = $item->quantity + 1;
        $item->save();
        return response()->json([
            'quantity' => $item->quantity,
        ]);
    }
    public function decreaseQuantity (Request $request) {
        $item = Cart::where('user_id', $request->user_id)->where('id', $request->id)->first();
        $item->quantity = $item->quantity - 1;
        $item->save();
        return response()->json([
            'quantity' => $item->quantity,
        ]);
    }
    public function removeItem (Request $request) {
        $item = Cart::where('user_id', $request->user_id)->where('id', $request->id)->first();
        $item->delete();
        return response()->json([
            'msg' => "Item deleted Successfuly",
        ]);
    }
    public function store(Request $request) {
            
        $existing_product = Cart::where('user_id', $request->user_id)
        ->where('name', $request->name)
        ->first();

        if($existing_product) {
            $existing_product->quantity = $existing_product->quantity + 1;
            $existing_product->save();

            return response()->json([
            'message' => 'Quantity has been updated successfully',
            ]);

        }
        else {

            $cart = Cart::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'price' => $request->price,
                'offer' => $request->offer,
                'image' => $request->image,
            ]);
    
            if($cart) {
                $cart_count = Cart::where('user_id', $request->user_id)->count();
                return response()->json([
                    'msg' => 'Product added to the cart successfully',
                    'count' => $cart_count,
                ], 200);
            }else {
                return response()->json([
                    'error' => 'Something Went Wrong'
                ], 202);
            }
        }
    }
}
