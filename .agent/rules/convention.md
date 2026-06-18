---
trigger: always_on
---

# .agent/rules/conventions.md

# Coding Conventions

Status: Final

## Purpose

Tujuan aturan ini adalah menjaga kode tetap idiomatik Laravel,
mudah dipahami developer Laravel biasa, dan konsisten ketika
ditulis oleh coding agent.

---

## General Principles

- Ikuti konvensi Laravel terlebih dahulu.
- Hindari abstraction yang tidak memberi nilai tambah.
- Prioritaskan keterbacaan dibanding cleverness.
- Tulis kode yang mudah dipelihara oleh developer lain.
- Jangan melakukan optimisasi prematur.

---

## PHP

Seluruh file PHP baru wajib menggunakan strict types.

```php
<?php

declare(strict_types=1);
```

Gunakan:

- PHP 8.1+ syntax.
- Constructor Property Promotion jika sesuai.
- Typed property.
- Return type declaration.
- Nullable type secara eksplisit.

---

## Dependency Injection

Gunakan constructor injection.

Benar:

```php
public function __construct(
    private PostService $postService,
) {
}
```

Salah:

```php
app(PostService::class);

resolve(PostService::class);

new PostService();
```

---

## Controller Rules

Controller harus tipis.

Tanggung jawab controller:

1. Authorization
2. Validation
3. Memanggil Action
4. Menentukan Response

Controller tidak boleh berisi business logic kompleks.

Benar:

Controller → Action → Response

Salah:

Controller → Query → Business Logic → Response

---

## Action Rules

Gunakan Action Pattern.

Satu Action = satu use case.

Contoh:

- CreatePostAction
- PublishPostAction
- SubmitRegistrationAction
- UploadDocumentAction

Action boleh menggunakan:

- Eloquent
- Repository
- Service
- Event

Action tidak boleh menangani HTTP concern.

---

## Action Return Value

Default:

Return Eloquent Model.

Contoh:

```php
$post = $this->createPostAction->execute($data);
```

Gunakan DTO hanya jika use case kompleks.

Contoh:

- Bulk process
- Export
- Integrasi eksternal
- Multi-result operation

Jangan membuat DTO untuk CRUD sederhana.

---

## Service Rules

Service digunakan untuk business flow yang lebih besar.

Service boleh mengorkestrasi beberapa Action.

Service tidak wajib ada untuk seluruh fitur.

Jangan membuat Service kosong.

---

## Repository Rules

Repository bersifat selektif.

Gunakan Repository jika:

- Query kompleks.
- Query reusable.
- Reporting.
- Export.
- Multi-table query dalam modul yang sama.

CRUD sederhana menggunakan Eloquent langsung.

Benar:

Action → Eloquent

atau

Action → Repository

Salah:

Repository untuk seluruh model tanpa kebutuhan nyata.

---

## Form Request

Gunakan Form Request untuk validasi.

Benar:

StorePostRequest

UpdateRegistrationRequest

Salah:

Validasi kompleks langsung di controller.

---

## Policy

Authorization menggunakan Policy.

Jangan hardcode role string di controller.

Benar:

```php
$this->authorize('update', $post);
```

Salah:

```php
if (auth()->user()->role === 'admin') {
}
```

---

## Events

Gunakan Event untuk side effect.

Contoh:

- RegistrationSubmitted
- MediaUploaded
- ContactMessageReceived

Listener:

- WriteAuditLog
- GenerateMediaVariants
- SendNotification

Jangan gunakan Event untuk menyembunyikan business flow utama.

---

## Testing

Tambahkan atau perbarui test jika perubahan menyentuh:

- Business logic
- Policy
- Endpoint penting
- Bug fix

Tidak wajib untuk:

- Perubahan view kecil
- Dokumentasi
- Refactor internal tanpa perubahan perilaku

---

## Minimal Change Principle

Ubah sesedikit mungkin.

Dilarang:

- Refactor besar tanpa diminta.
- Mengubah pola hanya karena preferensi pribadi.
- Memperluas scope task.

Perbaiki yang diminta.
Biarkan yang tidak diminta tetap seperti adanya.
