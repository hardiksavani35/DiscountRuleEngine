<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>
<p>I have created an e-commerce discount system that checks whether a discount will be applied and, if so, which one.</p>

<p>The logic implementation is as follows:</p>

<p><b>I have applied two types of discounts:</b></p>

<p><b>Discount based on all categories:</b> This discount doesn't check the category, but only the minimum cart total.</p>
<p><b>Discount based on a specific category:</b> This discount checks the specific category assigned to it, and the sum of the products in that category must be equal to or greater than a certain value for the rule to be applied.</p>
<p>I have used a column in the discount table called applies_to_all_categories (with values true or false):</p>

<p><b>True</b> indicates that the discount will apply regardless of the category, based only on the minimum cart total.</p>
<p><b>False</b> it means it's a category-based discount. In this case, all the products from the assigned category are added up, and the total must be equal to or greater than the minimum cart amount for the discount to apply.</p>

Additionally, I have added logic to ensure that discounts are only applied if the current date is within the specified "from" and "to" date range.

If multiple discounts are eligible, the most recent one will be applied.

<b>NOTE:</b> When you update an item's price, make sure to update the subtotal as well. This is important because, for category-specific discounts, I calculate the total by multiplying the product quantity by the price, and then subtract the discount from the subtotal.


<b>To run this project, follow these steps:</b>

git clone https://github.com/hardiksavani35/DiscountRuleEngine.git

cd DiscountRuleEngine

composer install

update database details in your .env file 

php artisan config:clear

php artisan migrate

php artisan db:seed --class=CategorySeeder

php artisan db:seed --class=DiscountSeeder

php artisan serve 


<b>To check using Postman, I have attached a collection in the root folder named "Discount Engine Rule.postman_collection.json."</b>

<p>Open Postman and import attached postman collection, it has 4 different case in the collection</p>
<p>1. Without discount (subtotal 300)</p>
<p>→ The cart has a subtotal of 300, which matches a record in our discount table, but the discount record has a category-specific requirement that doesn't match. Therefore, no discount is applied.</p>
<p>2 - With discount (subtotal 600)</p>
<p>→ The discount will be applied because we have a discount record where the minimum cart total required is 600, and there is no category-specific requirement (applies_to_all_categories is true).</p>
<p>3 - With discount based on category (subtotal = 600 and sum of Electronics & Furniture category is 300)</p>
<p>→ Discount will be applied because the condition is that the minimum cart total must be greater than or equal to 300, and the sum must be from the Electronics and Furniture categories.</p>
<p>4 - Without discount with category (subtotal = 1100 and sum of Electronics & Furniture category is 100)</p>
<p>→ Discount will be applied, but not category-based, because the sum of the Electronics and Furniture categories is 100, which does not meet the minimum cart total of 300 for specific categories. Therefore, the discount without the category condition will be applied.</p>

<b>To check using tests, I have created 4 tests under the "Feature" folder.</b>

<p>php artisan test tests/Feature/NoDiscountAvailable.php </p>
<p>php artisan test tests/Feature/PercentageDiscountAvailable.php</p>
<p>php artisan test tests/Feature/CategorySpecificDiscountAvailable.php</p>
