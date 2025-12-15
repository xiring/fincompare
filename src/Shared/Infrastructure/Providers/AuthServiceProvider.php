<?php

namespace Src\Shared\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role;
use Src\Auth\Domain\Entities\User as DomainUser;
use Src\Auth\Domain\Policies\PermissionPolicy;
use Src\Auth\Domain\Policies\RolePolicy;
use Src\Auth\Domain\Policies\UserPolicy;
use Src\Catalog\Domain\Entities\Attribute;
use Src\Catalog\Domain\Entities\Group;
use Src\Catalog\Domain\Entities\Product;
use Src\Catalog\Domain\Entities\ProductAttributeValue;
use Src\Catalog\Domain\Entities\ProductCategory;
use Src\Catalog\Domain\Policies\AttributePolicy;
use Src\Catalog\Domain\Policies\GroupPolicy;
use Src\Catalog\Domain\Policies\ProductAttributeValuePolicy;
use Src\Catalog\Domain\Policies\ProductCategoryPolicy;
use Src\Catalog\Domain\Policies\ProductPolicy;
use Src\Content\Domain\Entities\BlogPost;
use Src\Content\Domain\Entities\CmsPage;
use Src\Content\Domain\Entities\Faq;
use Src\Content\Domain\Policies\BlogPostPolicy;
use Src\Content\Domain\Policies\CmsPagePolicy;
use Src\Content\Domain\Policies\FaqPolicy;
use Src\Forms\Domain\Entities\Form;
use Src\Forms\Domain\Policies\FormPolicy;
use Src\Leads\Domain\Entities\Lead;
use Src\Leads\Domain\Policies\LeadPolicy;
use Src\Partners\Domain\Entities\Partner;
use Src\Partners\Domain\Policies\PartnerPolicy;

/**
 * AuthServiceProvider service provider.
 */
class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        DomainUser::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        SpatiePermission::class => PermissionPolicy::class,
        Partner::class => PartnerPolicy::class,
        Group::class => GroupPolicy::class,
        ProductCategory::class => ProductCategoryPolicy::class,
        Product::class => ProductPolicy::class,
        Attribute::class => AttributePolicy::class,
        ProductAttributeValue::class => ProductAttributeValuePolicy::class,
        Lead::class => LeadPolicy::class,
        BlogPost::class => BlogPostPolicy::class,
        CmsPage::class => CmsPagePolicy::class,
        Faq::class => FaqPolicy::class,
        Form::class => FormPolicy::class,
    ];

    public function boot(): void
    {
        // Admin role bypass policy checks
        Gate::before(function ($user, $ability) {
            return method_exists($user, 'hasRole') && $user->hasRole('admin') ? true : null;
        });
    }
}
