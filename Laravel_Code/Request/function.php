
<?php
// app/Http/Requests/StoreProductRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(
            $this->baseProductRules(),
            $this->imageRules(),
            $this->variantRules(),
            $this->metadataRules()
        );
    }

    protected function baseProductRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
        ];
    }

    protected function imageRules(): array
    {
        return [
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected function variantRules(): array
    {
        return [
            'variants' => 'sometimes|array',
            'variants.*.name' => 'required|string|max:100',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.sku' => 'sometimes|string|unique:variants,sku',
        ];
    }

    protected function metadataRules(): array
    {
        return [
            'metadata.tags' => 'sometimes|array',
            'metadata.tags.*' => 'string|max:50',
            'metadata.features' => 'sometimes|array',
            'metadata.features.*.name' => 'required_with:metadata.features|string',
            'metadata.features.*.value' => 'required_with:metadata.features|string',
        ];
    }

    public function validatedData(): array
    {
        $validated = $this->validated();

        return [
            'base' => $this->extractBaseData($validated),
            'images' => $validated['images'] ?? [],
            'variants' => $validated['variants'] ?? [],
            'metadata' => $validated['metadata'] ?? [],
        ];
    }

    protected function extractBaseData(array $data): array
    {
        return [
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'],
        ];
    }
}