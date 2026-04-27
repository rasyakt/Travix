<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealPreference extends Model
{
    protected $fillable = [
        'booking_passenger_id',
        'meal_type',
        'special_request',
        'dietary_restrictions',
        'allergies',
    ];

    protected $casts = [
        'dietary_restrictions' => 'array',
        'allergies' => 'array',
    ];

    public function passenger()
    {
        return $this->belongsTo(BookingPassenger::class, 'booking_passenger_id');
    }

    /**
     * Get available meal types
     */
    public static function getAvailableMeals(): array
    {
        return [
            'regular' => [
                'code' => 'REGULAR',
                'name' => 'Regular Meal',
                'description' => 'Standard in-flight meal',
                'icon' => '🍽️',
                'available_classes' => ['economy', 'premium_economy', 'business', 'first'],
            ],
            'vegetarian' => [
                'code' => 'VGML',
                'name' => 'Vegetarian Meal',
                'description' => 'Lacto-ovo vegetarian (no meat, fish, or poultry)',
                'icon' => '🥗',
                'available_classes' => ['economy', 'premium_economy', 'business', 'first'],
            ],
            'vegan' => [
                'code' => 'VEGAN',
                'name' => 'Vegan Meal',
                'description' => 'No animal products',
                'icon' => '🌱',
                'available_classes' => ['economy', 'premium_economy', 'business', 'first'],
            ],
            'halal' => [
                'code' => 'MOML',
                'name' => 'Muslim/Halal Meal',
                'description' => 'Prepared according to Islamic dietary laws',
                'icon' => '🕌',
                'available_classes' => ['economy', 'premium_economy', 'business', 'first'],
            ],
            'kosher' => [
                'code' => 'KSML',
                'name' => 'Kosher Meal',
                'description' => 'Prepared according to Jewish dietary laws',
                'icon' => '✡️',
                'available_classes' => ['business', 'first'],
            ],
            'hindu' => [
                'code' => 'HNML',
                'name' => 'Hindu Meal',
                'description' => 'Non-vegetarian Hindu meal',
                'icon' => '🕉️',
                'available_classes' => ['economy', 'premium_economy', 'business', 'first'],
            ],
            'diabetic' => [
                'code' => 'DBML',
                'name' => 'Diabetic Meal',
                'description' => 'Low sugar, high fiber',
                'icon' => '🩺',
                'available_classes' => ['economy', 'premium_economy', 'business', 'first'],
            ],
            'gluten_free' => [
                'code' => 'GFML',
                'name' => 'Gluten-Free Meal',
                'description' => 'No wheat, rye, barley, or oats',
                'icon' => '🌾',
                'available_classes' => ['economy', 'premium_economy', 'business', 'first'],
            ],
            'low_sodium' => [
                'code' => 'LSML',
                'name' => 'Low Sodium Meal',
                'description' => 'Reduced salt content',
                'icon' => '🧂',
                'available_classes' => ['business', 'first'],
            ],
            'seafood' => [
                'code' => 'SFML',
                'name' => 'Seafood Meal',
                'description' => 'Fish and seafood based',
                'icon' => '🐟',
                'available_classes' => ['business', 'first'],
            ],
            'child' => [
                'code' => 'CHML',
                'name' => 'Child Meal',
                'description' => 'Kid-friendly meal',
                'icon' => '👶',
                'available_classes' => ['economy', 'premium_economy', 'business', 'first'],
            ],
            'baby' => [
                'code' => 'BBML',
                'name' => 'Baby Meal',
                'description' => 'Pureed food for infants',
                'icon' => '🍼',
                'available_classes' => ['economy', 'premium_economy', 'business', 'first'],
            ],
        ];
    }

    /**
     * Get common dietary restrictions
     */
    public static function getDietaryRestrictions(): array
    {
        return [
            'no_pork' => 'No Pork',
            'no_beef' => 'No Beef',
            'no_seafood' => 'No Seafood',
            'no_nuts' => 'No Nuts',
            'no_dairy' => 'No Dairy',
            'no_eggs' => 'No Eggs',
            'no_gluten' => 'No Gluten',
            'no_soy' => 'No Soy',
            'lactose_intolerant' => 'Lactose Intolerant',
        ];
    }

    /**
     * Get common allergies
     */
    public static function getCommonAllergies(): array
    {
        return [
            'peanuts' => 'Peanuts',
            'tree_nuts' => 'Tree Nuts',
            'shellfish' => 'Shellfish',
            'fish' => 'Fish',
            'milk' => 'Milk',
            'eggs' => 'Eggs',
            'wheat' => 'Wheat',
            'soy' => 'Soy',
            'sesame' => 'Sesame',
        ];
    }

    /**
     * Check if meal is available for travel class
     */
    public static function isMealAvailableForClass(string $mealCode, string $travelClass): bool
    {
        $meals = self::getAvailableMeals();
        
        foreach ($meals as $meal) {
            if ($meal['code'] === $mealCode) {
                return in_array(strtolower($travelClass), $meal['available_classes']);
            }
        }

        return false;
    }
}
