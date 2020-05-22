<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'user' => 'required',
            'password' => 'required'
        ]);

        if($request->has("defaultCurrency")){
            if($request->get("defaultCurrency") != "" && !validateCurrency($request->get("defaultCurrency")))
                return $this->sendError('currencyError.', ['error' => 'Currency not found']);
        }

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['balance'] = 0;
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['user'] = $user->user;
        $success['id'] = $user->id;
        $success['balance'] = $user->balance;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['user' => $request->user, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user'] = $user->user;
            $success['id'] = $user->id;
            $success['balance'] = $user->balance;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @var User $user
     */
    public function update(Request $request, $id)
    {
        // Validate user exists.
        $user = User::find($id);

        if (!$user) {
            // Return error 404.
            return $this->sendError('Unauthorised.', ['error' => 'No user found with this ID']);
        }

        if (!$request->has("defaultCurrency")) {
            // Return error 404.
            return $this->sendError('currencyError.', ['error' => 'Currency is missing']);
        }

        if($request->get("defaultCurrency") != "" && !validateCurrency($request->get("defaultCurrency")))
            return $this->sendError('currencyError.', ['error' => 'Currency not found']);


        $username = $request->input('user');
        $defaultCurrency = $request->input('defaultCurrency');

        // Check PATCH data
        $flag = false;

        if ($username != null && $username != '') {
            $user->user = $username;
            $flag = true;
        }

        if ($defaultCurrency != null && $defaultCurrency != '') {
            $user->defaultCurrency = $defaultCurrency;
            $flag = true;
        }

        if ($flag) {
            $user->save();
            // Return 200
            return $this->sendResponse($user, 'Currency added.');
        } else {
            // Return 304 not updated
            return $this->sendError('Unauthorised.', ['error' => 'Error'],304);
        }
    }
}
