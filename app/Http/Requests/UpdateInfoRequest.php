<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Authenticated User Info",
 *      description="Update Authenticated User Info Request"
 * )
 *
 */

class UpdateInfoRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="first_name"
     * )
     * @var string
     *
     */

    public $first_name;

    /**
     * @OA\Property(
     *      title="last_name"
     * )
     * @var string
     */

    public $last_name;

    /**
     * @OA\Property(
     *      title="email"
     * )
     *
     * @var string
     *
     */
    public $email;

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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required'
        ];
    }
}