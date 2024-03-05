<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function changeProfileInformation(Request $request) {
        $fields = $request->validate([
            'user_id' => 'required',
            'name' => 'string',
            'email' => 'string|unique:users,email',
            'current_password' => 'required',
        ]);
    
        $user = User::find($fields['user_id']);
    
        if (!$user) {
            return response()->json([
                'msg' => 'User Not Found',
            ], 404);
        }
    
        if (!Hash::check($fields['current_password'], $user->password)) {
            return response()->json([
                'msg' => "The Password You Entered Doesn't Match With Our Credentials",
            ], 201);
        }
    
        $updateData = [
            'name' => $fields['name'],
        ];
    
        // Check if 'email' key exists and is different
        if (array_key_exists('email', $fields) && $fields['email'] !== $user->email) {
            $updateData['email'] = $fields['email'];
        }
    
        if ($user->update($updateData)) {
            return response()->json([
                'msg' => 'Profile Information Updated Successfully',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'msg' => "Something Went Wrong",
            ], 500);
        }
    }
    
    public function updatePassword(Request $request) {
        $fields = $request->validate([
            'user_id' => 'required',
            'current_password' => 'required',
            'password' => "string|required|confirmed"
        ]);
        $user = User::where('id', $request->user_id)->first();
        if($user) {
            if(!Hash::check($fields['current_password'], $user->password)) {
                return response()->json([
                    'msg' => "The Password You Enterd Dosen't Match With Our Credintals",
                ], 201);
            }else {
                $update = $user->update([
                    'password' => bcrypt($fields['password'])
                ]);
                if($update) {
                    return response()->json([
                        'msg' => 'Password Updated Successfuly'
                    ], 200);
                }else {
                    return response()->json([
                        'msg' => "Something Wen't Wrong "
                    ]);
                }
            }  
        }else {
            return response()->json([
                'msg' => 'User Not Found'
                
            ], 404);
        }
    }
    public function deleteUser(Request $request) {
        $fields = $request->validate([
            'user_id' => 'required',
            'current_password' => 'required',
        ]);
        $user = User::where('id', $request->user_id)->first();
        if($user) {
            if(!Hash::check($fields['current_password'], $user->password)) {
                return response()->json([
                    'msg' => "The Password You Enterd Dosen't Match With Our Credintals",
                ], 201);
            }else {
                $deleteUser = $user->delete();
                if($deleteUser) {
                    return response()->json([
                        'msg' => "User Deleted Successfully"
                    ], 200);
                }else {
                    return response()->json([
                        'msg' => 'Something Went Wrong'
                    ], 204);
                }
            }  
        }else {
            return response()->json([
                'msg' => 'User Not Found'
                
            ], 404);
        }
    }
}
