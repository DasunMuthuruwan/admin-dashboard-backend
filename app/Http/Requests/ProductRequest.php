<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Product Data",
 *      description="Store Product Request body data"
 * )
 */

class ProductRequest extends FormRequest
{

    /**
     * @OA\Property(
     *      title="title"
     * )
     *
     *  @var string
     */
    public $title;

    /**
     * @OA\Property(
     *      title="description"
     * )
     *
     *  @var string
     */
    public $description;

    /**
     * @OA\Property(
     *      title="image"
     * )
     *
     *  @var string
     */
    public $image;

    /**
     * @OA\Property(
     *      title="price"
     * )
     *
     *  @var float
     */
    public $price;

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
            'title' => 'required',
            'image' => 'required',
            'price' => 'required'
        ];
    }
}
