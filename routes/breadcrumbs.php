<?php

use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Role;

// Ana Sayfa
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(__('common.dashboard'), route('dashboard'));
});

// Ana Sayfa > Başlangıç
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('common.dashboard'), route('dashboard'));
});

// Ana Sayfa > Başlangıç > Analitik & Raporlama
Breadcrumbs::for('analytics.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common.analytics'), route('analytics.index'));
});

// Ana Sayfa > Başlangıç > Analitik & Raporlama > ShortLink Detaylı Analiz
Breadcrumbs::for('analytics.short-link', function (BreadcrumbTrail $trail, \App\Models\ShortLink $shortLink) {
    $trail->parent('analytics.index');
    $trail->push($shortLink->title ?? $shortLink->short_code, route('analytics.short-link', $shortLink));
});

// Ana Sayfa > Başlangıç > Analitik & Raporlama > QR Code Detaylı Analiz
Breadcrumbs::for('analytics.qr-code', function (BreadcrumbTrail $trail, \App\Models\QrCode $qrCode) {
    $trail->parent('analytics.index');
    $trail->push($qrCode->name, route('analytics.qr-code', $qrCode));
});

// Ana Sayfa > Başlangıç > Analitik & Raporlama > Brochure Detaylı Analiz
Breadcrumbs::for('analytics.brochure', function (BreadcrumbTrail $trail, \App\Models\Brochure $brochure) {
    $trail->parent('analytics.index');
    $trail->push($brochure->name, route('analytics.brochure', $brochure));
});

// Ana Sayfa > Başlangıç > Analitik & Raporlama > VCard Detaylı Analiz
Breadcrumbs::for('analytics.v-card', function (BreadcrumbTrail $trail, \App\Models\VCard $vCard) {
    $trail->parent('analytics.index');
    $trail->push($vCard->getLocalizedField('name') ?? __('common.v_card'), route('analytics.v-card', $vCard));
});

// Ana Sayfa > Başlangıç > Kullanıcı Yönetimi
Breadcrumbs::for('user-management.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common.user_management'), route('user-management.users.index'));
});

// Ana Sayfa > Başlangıç > Kullanıcı Yönetimi > Kullanıcılar
Breadcrumbs::for('user-management.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push(__('common.users'), route('user-management.users.index'));
});

// Home > Dashboard > User Management > Users > [User]
Breadcrumbs::for('user-management.users.show', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('user-management.users.index');
    $trail->push(ucwords($user->name), route('user-management.users.show', $user));
});


// Ana Sayfa > Başlangıç > Dosya Yönetimi
Breadcrumbs::for('file-management.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Dosya Yönetimi', route('file-management.index'));
});

// Ana Sayfa > Başlangıç > Dosya Yönetimi > [File]
Breadcrumbs::for('file-management.show', function (BreadcrumbTrail $trail, \App\Models\File $file) {
    $trail->parent('file-management.index');
    $trail->push($file->name, route('file-management.show', $file));
});

// Ana Sayfa > Başlangıç > Dosya Yönetimi > Düzenle [File]
Breadcrumbs::for('file-management.edit', function (BreadcrumbTrail $trail, \App\Models\File $file) {
    $trail->parent('file-management.show', $file);
    $trail->push('Düzenle', route('file-management.edit', $file));
});

// Ana Sayfa > Başlangıç > QR Kod Yönetimi
Breadcrumbs::for('qr-code.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common.qr_code_management'), route('qr-code.index'));
});

// Ana Sayfa > Başlangıç > QR Kod Yönetimi > Yeni QR Kod
Breadcrumbs::for('qr-code.create', function (BreadcrumbTrail $trail) {
    $trail->parent('qr-code.index');
    $trail->push(__('common.create_qr_code'), route('qr-code.create'));
});

// Ana Sayfa > Başlangıç > QR Kod Yönetimi > [QR Code]
Breadcrumbs::for('qr-code.show', function (BreadcrumbTrail $trail, \App\Models\QrCode $qrCode) {
    $trail->parent('qr-code.index');
    $trail->push($qrCode->name, route('qr-code.show', $qrCode));
});

// Ana Sayfa > Başlangıç > QR Kod Yönetimi > Düzenle [QR Code]
Breadcrumbs::for('qr-code.edit', function (BreadcrumbTrail $trail, \App\Models\QrCode $qrCode) {
    $trail->parent('qr-code.show', $qrCode);
    $trail->push('Düzenle', route('qr-code.edit', $qrCode));
});

// Ana Sayfa > Başlangıç > Ortam Kütüphanesi
Breadcrumbs::for('media-library.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common.media_library'), route('media-library.index'));
});

// Ana Sayfa > Başlangıç > Ayarlar
Breadcrumbs::for('settings', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common.settings'), route('settings.index'));
});

// Ana Sayfa > Başlangıç > API Token'ları
Breadcrumbs::for('api-tokens.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common.api_tokens'), route('api-tokens.index'));
});

// Ana Sayfa > Başlangıç > Kategori Yönetimi
Breadcrumbs::for('categories.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common.category_management'), route('categories.index'));
});

// Ana Sayfa > Başlangıç > Kategori Yönetimi > Yeni Kategori
Breadcrumbs::for('categories.create', function (BreadcrumbTrail $trail) {
    $trail->parent('categories.index');
    $trail->push(__('common.create_category'), route('categories.create'));
});

// Ana Sayfa > Başlangıç > Kategori Yönetimi > Düzenle [Category]
Breadcrumbs::for('categories.edit', function (BreadcrumbTrail $trail, \App\Models\Category $category) {
    $trail->parent('categories.index');
    $trail->push('Düzenle: ' . $category->name, route('categories.edit', $category));
});

// Ana Sayfa > Başlangıç > vCard Yönetimi
Breadcrumbs::for('v-card.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common.v_card_management'), route('v-card.index'));
});

// Ana Sayfa > Başlangıç > vCard Yönetimi > Yeni vCard
Breadcrumbs::for('v-card.create', function (BreadcrumbTrail $trail) {
    $trail->parent('v-card.index');
    $trail->push(__('common.create_v_card'), route('v-card.create'));
});

// Ana Sayfa > Başlangıç > vCard Yönetimi > [VCard]
Breadcrumbs::for('v-card.show', function (BreadcrumbTrail $trail, \App\Models\VCard $vCard) {
    $trail->parent('v-card.index');
    $name = $vCard->getLocalizedField('name') ?? 'vCard #' . $vCard->id;
    $trail->push($name, route('v-card.show', $vCard));
});

// Ana Sayfa > Başlangıç > vCard Yönetimi > Düzenle [VCard]
Breadcrumbs::for('v-card.edit', function (BreadcrumbTrail $trail, \App\Models\VCard $vCard) {
    $trail->parent('v-card.show', $vCard);
    $trail->push(__('common.edit'), route('v-card.edit', $vCard));
});

// Ana Sayfa > Başlangıç > vCard Şablon Yönetimi
Breadcrumbs::for('v-card-template.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common.vcard_template_management'), route('v-card-template.index'));
});

// Ana Sayfa > Başlangıç > vCard Şablon Yönetimi > Yeni Şablon
Breadcrumbs::for('v-card-template.create', function (BreadcrumbTrail $trail) {
    $trail->parent('v-card-template.index');
    $trail->push(__('common.create_vcard_template'), route('v-card-template.create'));
});

// Ana Sayfa > Başlangıç > vCard Şablon Yönetimi > [Template]
Breadcrumbs::for('v-card-template.show', function (BreadcrumbTrail $trail, \App\Models\VCardTemplate $vCardTemplate) {
    $trail->parent('v-card-template.index');
    $trail->push($vCardTemplate->name, route('v-card-template.show', $vCardTemplate));
});

// Ana Sayfa > Başlangıç > vCard Şablon Yönetimi > Düzenle [Template]
Breadcrumbs::for('v-card-template.edit', function (BreadcrumbTrail $trail, \App\Models\VCardTemplate $vCardTemplate) {
    $trail->parent('v-card-template.index');
    $trail->push(__('common.edit') . ': ' . $vCardTemplate->name, route('v-card-template.edit', $vCardTemplate));
});

// Ana Sayfa > Başlangıç > Kitapçık Yönetimi
Breadcrumbs::for('brochure.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common.brochure_management'), route('brochure.index'));
});

// Ana Sayfa > Başlangıç > Kitapçık Yönetimi > Yeni Kitapçık
Breadcrumbs::for('brochure.create', function (BreadcrumbTrail $trail) {
    $trail->parent('brochure.index');
    $trail->push(__('common.create_brochure'), route('brochure.create'));
});

// Ana Sayfa > Başlangıç > Kitapçık Yönetimi > [Brochure]
Breadcrumbs::for('brochure.show', function (BreadcrumbTrail $trail, \App\Models\Brochure $brochure) {
    $trail->parent('brochure.index');
    $trail->push($brochure->name, route('brochure.show', $brochure));
});

// Ana Sayfa > Başlangıç > Kitapçık Yönetimi > Düzenle [Brochure]
Breadcrumbs::for('brochure.edit', function (BreadcrumbTrail $trail, \App\Models\Brochure $brochure) {
    $trail->parent('brochure.show', $brochure);
    $trail->push(__('common.edit'), route('brochure.edit', $brochure));
});

// Ana Sayfa > Başlangıç > Link Kısaltma
Breadcrumbs::for('short-link.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('common.short_link_list'), route('short-link.index'));
});

// Ana Sayfa > Başlangıç > Link Kısaltma > Yeni Link Kısalt
Breadcrumbs::for('short-link.create', function (BreadcrumbTrail $trail) {
    $trail->parent('short-link.index');
    $trail->push(__('common.create_short_link'), route('short-link.create'));
});

// Ana Sayfa > Başlangıç > Link Kısaltma > [ShortLink]
Breadcrumbs::for('short-link.show', function (BreadcrumbTrail $trail, \App\Models\ShortLink $shortLink) {
    $trail->parent('short-link.index');
    $trail->push($shortLink->title ?? $shortLink->short_code, route('short-link.show', $shortLink));
});

// Ana Sayfa > Başlangıç > Link Kısaltma > Düzenle [ShortLink]
Breadcrumbs::for('short-link.edit', function (BreadcrumbTrail $trail, \App\Models\ShortLink $shortLink) {
    $trail->parent('short-link.show', $shortLink);
    $trail->push(__('common.edit'), route('short-link.edit', $shortLink));
});
