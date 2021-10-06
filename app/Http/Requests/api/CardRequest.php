<?php

namespace App\Http\Requests\api;


use App\Models\Card;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CardRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'number' => ['required', 'max:16', 'min:16',
                function ($attribute, $value, $fail) {
                    $card = Card::query()->where('number', $value)
                        ->first();
                    if ($card && $card->verify) {
                        $fail(__('messages.card_exists'));
                    } elseif ($card && !$card->verify) {
                        $card->delete();
                    }
                }
            ],
            'expire' => 'required|max:4|min:4',
        ];
    }
}
