# Gii Tester
Gii Tester (Yii2 Test Generator Code) with Person Relationship with PostgreSQL

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
composer require allankaio/giitester
```

or add

```
"allankaio/giitester": "^1"
```

to the `require` section of your `composer.json` file.


## Configuration

1. Then you must add this parameter to config\params.php.
```php
'testepath' => 'tests'
```

3. Then you must add this code at your config\web.php.

```php
'components'=>[
	'user' => [
		'class' => 'webvimark\modules\UserManagement\components\UserConfig',

		// Comment this if you don't want to record user logins
		'on afterLogin' => function($event) {
				\webvimark\modules\UserManagement\models\UserVisitLog::newVisitor($event->identity->id);
			}
	],
],

'modules' => [
      'user-management' => [
            'class' => 'webvimark\modules\UserManagement\UserManagementModule',
		'on beforeAction'=>function(yii\base\ActionEvent $event) {
                  if ( $event->action->uniqueId == 'user-management/auth/login' ){
                        $event->action->controller->layout = 'loginLayout.php';
                  };
            },
	],
      'gridview' => [
          'class' => '\kartik\grid\Module',
      ],
      'datecontrol' => [
          'class' => '\kartik\datecontrol\Module',
      ],
  ],
```
See gridview settings on http://demos.krajee.com/grid#module

See datecontrol settings on http://demos.krajee.com/datecontrol#module

3. In your config/console.php (this is needed for migrations and working with console)

```php
'modules'=>[
	'user-management' => [
		'class' => 'webvimark\modules\UserManagement\UserManagementModule',
	        'controllerNamespace'=>'vendor\webvimark\modules\UserManagement\controllers', // To prevent yii help from crashing
	],
],
```

4. Run migrations
```php
./yii migrate --migrationPath=vendor/webvimark/module-user-management/migrations/
```

See user-management settings on https://github.com/webvimark/user-management

## Usage :
Go to your gii tools, and notice the new EasYii Gii Generator for models & CRUD


# Features
## Model :
1. Generate representation columns(RepresentingColumn)
2. Generate CPF/CNPJ validator
3. Generate e-mail validator
4. Specify your label name / attribute for foreign keys and fields based on the DBMS comment

## CRUD :
1. Generate all CRUD with wildcard (*) of table
2. Generate related input output
3. Export to various formats
4. Expandable / collapsible row at index grid view for related data
5. Views with or without TabularForms
6. Custom fields for dates (DateControl)
7. Custom fields for foreign keys (Select2 and RepresentingColumn)
8. Exclusion constraint based on DBMS actions
9. Addition of the security module (User management module for Yii 2)
10. Custom fields for input file (FileInput)
11. View foreign key data based on RepresentingColumn

## Tests :
1. Generate all tests of a table
2. Generate tests for CRUD
3. Generate tests for data types
4. Generate tests for Email/CPF

## Migration Generator :
1. Generate migration from your database structure (based on : https://github.com/deesoft/yii2-gii)

I'm open for any improvement


# Thanks To
1. Jiwanndaru (jiwanndaru@gmail.com) for creating the tradition
2. kartik-v (https://github.com/kartik-v) for most of widgets
3. schmunk42 (https://github.com/schmunk42) for bootstrap & model base & extension
4. mdmunir (https://github.com/mdmunir) for JsBlock & Migration Generator (from https://github.com/deesoft/yii2-gii)
5. mootensai (https://github.com/mootensai) for yii2-enhanced-gii (https://github.com/mootensai/yii2-enhanced-gii)
6. thtmorais (https://github.com/thtmorais/easyiigii)
7. petersonsilvadejesus (https://github.com/petersonsilvadejesus)

# Developers
2. Allan Kaio Brito Macedo (https://github.com/AllanKaio21)

