# Supabase PHP SDK 📦🚀
Uma biblioteca **leve e autônoma** para interagir com o **Supabase** em PHP, sem dependências externas.  
Ideal para aplicações que precisam de **CRUD simples e eficiente** sem precisar de frameworks.

---

## 📌 Características
✅ **Autônoma** → Sem dependências externas.  
✅ **Fácil de Usar** → Métodos simples para CRUD.  
✅ **Leve e Eficiente** → Apenas **PHP puro** com `cURL`.  
✅ **Suporte a Soft Deletes** → Para excluir dados sem removê-los do banco.  
✅ **Código Modular** → Separado em classes e interfaces para fácil manutenção.  

---

## 📌 Instalação
Se você ainda não tem **Composer**, instale primeiro:  
🔗 [Instruções para instalar Composer](https://getcomposer.org/download/)  

Agora instale a biblioteca:
```sh
composer require blume01/supabase-php-sdk
```

---

## 📌 Configuração
Antes de usar, **registre sua URL e API Key** do Supabase:
```php
require 'vendor/autoload.php';

use Supabase\SupabaseClient;

$supabase = new SupabaseClient(
    'supabase-url', 
    'secret-api-key'
);
```

---

## 📌 Exemplos de Uso

### 🟢 **Criar um Registro**
```php
$response = $supabase->create('users', [
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

print_r($response);
```

### 🔵 **Ler Registros**
```php
$response = $supabase->read('users', ['email' => 'john@example.com']);
print_r($response);
```

### 🟠 **Atualizar um Registro**
```php
$response = $supabase->update('users', ['name' => 'John Updated'], ['email' => 'john@example.com']);
print_r($response);
```

### 🔴 **Excluir um Registro (Hard Delete)**
```php
$response = $supabase->delete('users', ['email' => 'john@example.com']);
print_r($response);
```

### ⚪ **Exclusão Lógica (Soft Delete)**
```php
$response = $supabase->softDelete('users', ['email' => 'john@example.com']);
print_r($response);
```

---

## 📌 Tratamento de Erros
Caso algo dê errado, um erro será lançado:
```php
try {
    $response = $supabase->create('users', []);
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
```

---

## 📌 Licença
Este projeto é licenciado sob a licença **MIT**.  
Sinta-se livre para usar e modificar. 🚀

---

📌 **Dúvidas? Sugestões?**  
Abra uma **Issue** ou entre em contato!