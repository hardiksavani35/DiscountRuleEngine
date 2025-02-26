<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>
<p>I have developed an e-commerce application that checks whether a discount will be applied or not. I have managed two types of discounts: one that involves only the <b>min_cart_total</b>, and includes <b>active_from</b> and <b>active_to</b> dates; and the other that includes category checking, where the <b>min_cart_total</b> must be equal to or greater than the sum of selected category products for that discount, along with the <b>active_from</b> and <b>active_to</b> dates.</p>

<p>First, I fetch all the discounts that are currently active, meaning the <b>active_from</b> and <b>active_to</b> dates are between today’s date. Then, I check the category-based restrictions. I manage these restrictions using the <b>applies_to_all_categories</b> column. If this value is <b>true</b>, we don't check the category; only the <b>min_cart_total</b>, <b>active_from</b>, and <b>active_to</b> are considered. But if it’s false, I add an additional condition: the sum of all products linked to the discount's specified category must meet or exceed the <b>min_cart_total</b>. This sum is then used against the <b>min_cart_total</b>, not the <b>subtotal</b>, when <b>applies_to_all_categories</b> is false</b>. If multiple discounts are eligible, the most recent one will be applied.</p>

<p>After that, I prepare a response that shows which discount was applied, the subtotal, the amount to be paid, and the total discount.</p>

<b>NOTE:</b> When you update an item's price in <b>Postman</b>, make sure to update the subtotal as well. This is important because, for category-specific discounts, I calculate the total by multiplying the product quantity by the price, and then subtract the discount from the subtotal.

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

![image](https://github.com/user-attachments/assets/e2f650d2-93b3-4c1a-8df4-0c64acf75408)
![image](https://github.com/user-attachments/assets/8f4bbea4-4392-483a-8b6b-20e6b4a72139)

