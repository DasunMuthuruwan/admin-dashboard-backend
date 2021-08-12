<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Or Update Role Data",
 *      description="Store or Update Role Request body data"
 * )
 */


class RoleRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="name"
     * )
     *
     *  @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="per",
     *      @OA\Items(
     *          type="integer"
     *    )
     * )
     *
     *  @var array
     */
    public $per;

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
            'name' => 'required',
        ];
    }
}
