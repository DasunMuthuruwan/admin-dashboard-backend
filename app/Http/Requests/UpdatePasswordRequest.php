<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *      title="Update Authenticated User Password",
 *      description="Update Authenticated User Password Request"
 * )
 *
 */

class UpdatePasswordRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="password"
     * )
     *
     * @var string
     */

    public $password;

    /**
     * @OA\Property(
     *      title="password_confirmation"
     * )
     *
     * @var string
     */

    public $password_confirmation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|confirmed'
        ];
    }
}
