# إجراءات الطلب والدفع في تطبيق توصيل الطعام

## جدول المحتويات

### 0. [مقدمة](#1-مقدمة)

### 1. [1. استعراض الأطباق](#2-استعراض-الأطباق)

### 2. [2. إضافة الأصناف للسلة](#3-إضافة-الأصناف-للسلة)

### 3. [3. عرض محتويات السلة](#4-عرض-محتويات-السلة)

### 4. [4. تحديث كميات الأصناف](#5-تحديث-كميات-الأصناف)

### 5. [5. تطبيق كوبون خصم](#6-تطبيق-كوبون-خصم)

### 6. [6. إنشاء الطلب](#7-إنشاء-الطلب)

### 7. [7. معالجة الدفع](#8-معالجة-الدفع)

### 8. [8. رسائل النجاح](#9-رسائل-النجاح)

### 9. [9. رسائل الخطأ](#10-رسائل-الخطأ)

### 10. [10. إدارة الطلبات](#11-إدارة-الطلبات)

## مقدمة

يوضح هذا الدليل الخطوات الكاملة لعملية الطلب في تطبيق توصيل الطعام، بدءًا من استعراض الأطباق وحتى تأكيد استلام الطلب. تم تصميم واجهة برمجة التطبيقات (API) لتكون سهلة الاستخدام وتوفر تجربة مستخدم سلسة.

## 1. استعراض الأطباق

### 1.1 تصفح قائمة الأطباق

GET /api/dishes

**الاستجابة الناجحة:**

{
"data": [
{
"id": 1,
"name": "بيتزا مارغريتا",
"description": "بيتزا إيطالية تقليدية مع جبنة موزاريلا وطماطم",
"price": 35.00,
"image": "dishes/pizza.jpg",
"category_id": 1,
"chef_id": 5,
"is_available": true,
"average_rating": 4.5,
"created_at": "2025-06-01T10:00:00.000000Z",
"updated_at": "2025-06-01T10:00:00.000000Z"
}
],
"links": {
"first": "[http://api.example.com/dishes?page=1"](http://api.example.com/dishes?page=1"),
"last": "[http://api.example.com/dishes?page=5"](http://api.example.com/dishes?page=5"),
"prev": null,
"next": "[http://api.example.com/dishes?page=2"](http://api.example.com/dishes?page=2")
},
"meta": {
"current_page": 1,
"from": 1,
"last_page": 5,
"path": "[http://api.example.com/dishes"](http://api.example.com/dishes"),
"per_page": 15,
"to": 15,
"total": 75
}
}

### 1.2 تصفية الأطباق حسب الفئة

GET /api/dishes?category=1

### 1.3 البحث عن أطباق

GET /api/dishes?search=بيتزا

## 2. إضافة الأصناف للسلة

### 2.1 إضافة صنف للسلة

POST /api/cart/items
Content-Type: application/json
Authorization: Bearer {token}

{
"dish_id": 1,
"quantity": 2,
"size_id": 3,
"notes": "بدون بصل، إضافة جبنة إضافية"
}

**الاستجابة الناجحة:**

{
"message": "تمت إضافة الصنف إلى السلة بنجاح",
"data": {
"id": 15,
"cart_id": 7,
"dish_id": 1,
"quantity": 2,
"size_id": 3,
"unit_price": 45.00,
"total_price": 90.00,
"notes": "بدون بصل، إضافة جبنة إضافية",
"created_at": "2025-07-01T14:30:45.000000Z",
"updated_at": "2025-07-01T14:30:45.000000Z",
"dish": {
"id": 1,
"name": "بيتزا مارغريتا",
"image": "dishes/pizza.jpg"
},
"size": {
"id": 3,
"name": "كبيرة",
"price_multiplier": 1.3
}
}
}

### 2.2 تحديث كمية صنف في السلة

PUT /api/cart/items/15
Content-Type: application/json
Authorization: Bearer {token}
{
"quantity": 3
}

### 2.3 حذف صنف من السلة

DELETE /api/cart/items/15
Content-Type: application/json
Authorization: Bearer {token}

## 3. عرض محتويات السلة

GET /api/cart
Authorization: Bearer {token}

### 3.1 الاستجابة الناجحة

{
"data": {
"id": 7,
"user_id": 3,
"total": 185.50,
"discount": 0.00,
"subtotal": 185.50,
"created_at": "2025-07-01T14:25:30.000000Z",
"updated_at": "2025-07-01T14:30:45.000000Z",
"items": [
{
"id": 15,
"dish_id": 1,
"quantity": 2,
"unit_price": 45.00,
"total_price": 90.00,
"notes": "بدون بصل، إضافة جبنة إضافية",
"dish": {
"id": 1,
"name": "بيتزا مارغريتا",
"image": "dishes/pizza.jpg"
},
"size": {
"id": 3,
"name": "كبيرة",
"price_multiplier": 1.3
}
},
{
"id": 16,
"dish_id": 5,
"quantity": 1,
"unit_price": 25.00,
"total_price": 25.00,
"notes": "إضافة مايونيز",
"dish": {
"id": 5,
"name": "برجر لحم",
"image": "dishes/burger.jpg"
},
"size": {
"id": 1,
"name": "عادية",
"price_multiplier": 1.0
}
}
]
}
}

### 3.2 الرسالة عند فارغة السلة

{
"message": "سلة التسوق فارغة"
}

## 4. تحديث كميات الأصناف

### 4.1 زيادة كمية صنف

PUT /api/cart/items/15/increase
Authorization: Bearer {token}

### 4.2 تقليل كمية صنف

PUT /api/cart/items/15/decrease
Authorization: Bearer {token}

## 5. تطبيق كوبون خصم

### 5.1 تطبيق كود الخصم

POST /api/cart/apply-coupon
Content-Type: application/json
Authorization: Bearer {token}

{
"code": "SUMMER2025"
}

### 5.1 الرسالة عند تطبيق كوبون

{
"message": "تم تطبيق كود الخصم بنجاح",
"data": {
"id": 7,
"user_id": 3,
"total": 166.95,
"discount": 18.55,
"subtotal": 185.50,
"coupon": {
"code": "SUMMER25",
"discount_percent": 10,
"description": "خصم 10% على إجمالي الطلب"
}
}
}

### 5.2 الرسالة عند عدم وجود كوبون

{
"message": "كوبون غير صحيح"
}

### 5.3 حذف كوبون

DELETE /api/cart/remove-coupon
Authorization: Bearer {token}

## 6. إنشاء الطلب

### 6.1 إنشاء طلب جديد

POST /api/orders
Content-Type: application/json
Authorization: Bearer {token}

{
"address_id": 3,
"payment_method": "credit_card",
"notes": "الرجاء عدم إضافة مكعبات ثلج مع المشروبات"
}

### الاستجابة الناجحة

{
"message": "تم إنشاء الطلب بنجاح",
"data": {
"id": "ORD-20250701-001",
"user_id": 3,
"status": "pending",
"total": 166.95,
"subtotal": 185.50,
"delivery_fee": 10.00,
"discount": 18.55,
"payment_method": "credit_card",
"payment_status": "pending",
"address": {
"id": 3,
"title": "المنزل",
"address_line_1": "شارع الملك فهد",
"address_line_2": "حي السلامة",
"city": "جدة",
"postal_code": "12345",
"phone": "0501234567"
},
"items": [
{
"id": 1,
"dish_id": 1,
"quantity": 2,
"unit_price": 45.00,
"total_price": 90.00,
"notes": "بدون بصل، إضافة جبنة إضافية",
"dish": {
"id": 1,
"name": "بيتزا مارغريتا"
}
}
],
"created_at": "2025-07-01T14:35:20.000000Z"
}
}

## 7. معالجة الدفع

### 7.1 معالجة الدفع

POST /api/payments
Content-Type: application/json
Authorization: Bearer {token}

{
"order_id": "ORD-20250701-001",
"payment_method": "credit_card",
"card_number": "4111111111111111",
"exp_month": "12",
"exp_year": "2027",
"cvc": "123"
}

## االاستجابة الناجحة

{
"message": "تمت معالجة الدفع بنجاح",
"data": {
"id": "PAY-001",
"order_id": "ORD-20250701-001",
"amount": 176.95,
"status": "completed",
"payment_method": "credit_card",
"transaction_id": "txn_1J8hT2KZvKYlo2CwK5J8XkLk",
"payment_details": {
"last4": "1111",
"brand": "visa"
},
"created_at": "2025-07-01T14:37:05.000000Z"
}
}

## 8. رسائل النجاح

### 8.1 رسائل نجاح السلة

تمت إضافة الصنف إلى السلة بنجاح
تم تحديث كمية الصنف بنجاح
تم حذف الصنف من السلة بنجاح
تم تطبيق كود الخصم بنجاح
تمت إزالة كود الخصم بنجاح

### 8.2 رسائل نجاح الطلب

تم إنشاء الطلب بنجاح
تم تحديث حالة الطلب بنجاح
تم إلغاء الطلب بنجاح

### 8.3 رسائل نجاح الدفع

تمت معالجة الدفع بنجاح
تم تأكيد استلام الدفع
تم إرسال إشعار الدفع إلى بريدك الإلكتروني

## 9. رسائل الخطأ

### 9.1 أخطاء السلة

الكمية المطلوبة غير متوفرة (400)
الحد الأقصى للكمية المسموح بها هو 10 (400)
كود الخصم غير صالح أو منتهي الصلاحية (400)
الحد الأدنى للطلب 50 ريال (400)

### 9.2 أخطاء الطلب

العنوان المحدد غير موجود (404)
لا يمكن إتمام الطلب - السلة فارغة (400)
لا يمكن إجراء تعديل على الطلب الحالي (403)
تم تجاوز وقت التعديل المسموح به (403)

### 9.3 أخطاء الدفع

فشلت عملية الدفع - الرصيد غير كافي (402)
بيانات البطاقة غير صالحة (400)
انتهت صلاحية البطاقة (400)
تم رفض المعاملة من قبل البنك (402)

## 10. إدارة الطلبات

### 10.1 عرض تفاصيل الطلب

GET /api/orders/ORD-20250701-001
Authorization: Bearer {token}

### 10.2 تتبع حالة الطلب

GET /api/orders/ORD-20250701-001/tracking
Authorization: Bearer {token}

### 10.3 إلغاء الطلب

DELETE /api/orders/ORD-20250701-001
Authorization: Bearer {token}

### 10.4 تقييم الطلب

POST /api/orders/ORD-20250701-001/review
Content-Type: application/json
Authorization: Bearer {token}

{
"rating": 5,
"comment": "رائع جداً"
}
