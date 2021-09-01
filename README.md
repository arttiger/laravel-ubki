# Laravel-Ubki

<p align="center">
    <a href="https://github.styleci.io/repos/000000000"><img src="https://github.styleci.io/repos/000000000/shield?style=flat" alt="StyleCI Status"></a>
    <a href="https://packagist.org/packages/arttiger/laravel-ubki"><img src="https://img.shields.io/packagist/dt/arttiger/laravel-ubki?style=flat" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/arttiger/laravel-ubki"><img src="https://img.shields.io/packagist/v/arttiger/laravel-ubki?style=flat" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/arttiger/laravel-ubki"><img src="https://img.shields.io/packagist/l/arttiger/laravel-ubki?style=flat" alt="License"></a>
</p>

[Украинское бюро кредитных историй (УБКИ)][link-ubki] занимается сбором, хранением, обработкой и предоставлением кредитных историй. УБКИ получает информацию о заемщиках от банков, страховых компаний, лизинговых компаний, кредитных союзов и других финансовых институтов. Информация передается на добровольной основе и только при наличии письменного согласия заемщика.

Для автоматизации взаимодействия с УБКИ существует [web-сервис][link-ubki-api], который принимает запросы, обрабатывает и выдает ответ в зависимости от типа запроса. 

This package allows you to simply and easily work with the web-service UBKI.

## Installation

Install the package via composer:

``` bash
$ composer require arttiger/laravel-ubki
```

Next, you need to run migrations:
```bash
$ php artisan migrate
```

### Configuration

In order to edit the default configuration you may execute:
```
php artisan vendor:publish --provider="Arttiger\Ubki\UbkiServiceProvider"
```

After that, `config/ubki.php` will be created.

### Environment

Set environment variable (`.env`)
```
UBKI_TEST_MODE=true
UBKI_ACCOUNT_LOGIN=
UBKI_ACCOUNT_PASSWORD=
UBKI_AUTH_URL=https://secure.ubki.ua/b2_api_xml/ubki/auth
UBKI_REQUEST_URL=https://secure.ubki.ua/b2_api_xml/ubki/xml
UBKI_UPLOAD_URL=https://secure.ubki.ua/upload/data/xml
UBKI_TEST_AUTH_URL=https://secure.ubki.ua:4040/b2_api_xml/ubki/auth
UBKI_TEST_REQUEST_URL=https://secure.ubki.ua:4040/b2_api_xml/ubki/xml
UBKI_TEST_UPLOAD_URL=https://secure.ubki.ua:4040/upload/data/xml
```

## Usage
Add `IntegratorUbki`-trait to the model with client data:
```
    use Arttiger\Ubki\Traits\IntegratorUbki;

    class Loan extends Model
    {
        use IntegratorUbki;
        ...
    }
```

Set the necessary the mapping variables in `config/ubki.php`:

```
'model_data' => [
  'okpo'  => 'inn',           // ИНН
  'lname' => 'lastName',      // Фамилия
  'fname' => 'firstName',     // Имя
  'mname' => 'middleName',    // Отчество
  'bdate' => 'birth_date',    // Дата рождения (гггг-мм-дд)
  'dtype' => 'passport_type', // Тип паспорта (см. справочник "Тип документа")
  'dser'  => 'passport_ser',  // Серия паспорта или номер записи ID-карты
  'dnom'  => 'passport_num',  // Номер паспорта или номер ID-карты
  'ctype' => 'contact_type',  // Тип контакта (см. справочник "Тип контакта")
  'cval'  => 'contact_val',   // Значение контакта (например - "+380951111111")
  'foto'  => 'foto',          // <base64(Фото)>
],
```
This map establishes the correspondence between the attributes of your model and the required query fields in UBKI.

Add a new method `ubkiAttributes()` to the class to add the necessary attributes and fill them with data:

```
    use Arttiger\Ubki\Traits\IntegratorUbki;

    class Loan extends Model
    {
        use IntegratorUbki;
        ...
        
        public function ubkiAttributes($params = [])
        {
            $client_data = json_decode($this->attributes['client_data']);
            $this->attributes['inn']        = trim($client_data->code); 
            $this->attributes['lastName']   = trim($client_data->lastName); 
            ...
        }
    }
```
You can use other ways to create custom attributes that you specified in `'model_data'` (`config/ubki.php`).

Now, you can get data from UBKI:
```php
$loan = Loan::find(1); 
$result = $loan->ubki();
```
`$result['response']` - xml response from UBKI (standard report).

You can also pass parameters:
```php
$result = $loan->ubki($params);
```
- `$params['report']` - report alias, if you need other reports; 
- `$params['request_id']` - your request ID (if necessary);
- `$params['lang']` - search language;
- `$params['delete_all_history']` - set true if you want delete all history;

You can send the loan data to UBKI:
```php
$result = $loan->ubki_upload($params);
```
`$params` - will be passed to the ubkiAttributes() method in the model.

For switching between accounts you should add to params:
- to select second account
```php
$params = [
    'test' => false,
    'use_second_account_login' => true
];
```

- to select main account
```php
$params = [
    'test' => false,
    'use_second_account_login' => false
];
```

if you not select what account to use, last used account will be executed.

## Change log

Please see the [changelog](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

[Volodymyr Farylevych](https://github.com/arttiger)

## License

Please see the [license file](LICENSE.md) for more information.

[link-ubki]: https://www.ubki.ua/
[link-ubki-api]: https://sites.google.com/ubki.ua/doc/%D0%BE%D0%B1%D1%89%D0%B8%D0%B5-%D0%BF%D1%80%D0%B8%D0%BD%D1%86%D0%B8%D0%BF%D1%8B-%D0%B2%D0%B7%D0%B0%D0%B8%D0%BC%D0%BE%D0%B4%D0%B5%D0%B9%D1%81%D1%82%D0%B2%D0%B8%D1%8F

