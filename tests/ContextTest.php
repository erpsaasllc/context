<?php

namespace ERPSAAS\Context\Tests;

use ERPSAAS\Context\Features;
use ERPSAAS\Context\Context;

class ContextTest extends OrchestraTestCase
{
    public function test_roles_can_be_registered()
    {
        Context::$permissions = [];
        Context::$roles = [];

        Context::role('admin', 'Admin', [
            'read',
            'create',
        ])->description('Admin Description');

        Context::role('editor', 'Editor', [
            'read',
            'update',
            'delete',
        ])->description('Editor Description');

        $this->assertTrue(Context::hasPermissions());

        $this->assertEquals([
            'create',
            'delete',
            'read',
            'update',
        ], Context::$permissions);
    }

    public function test_roles_can_be_json_serialized()
    {
        Context::$permissions = [];
        Context::$roles = [];

        $role = Context::role('admin', 'Admin', [
            'read',
            'create',
        ])->description('Admin Description');

        $serialized = $role->jsonSerialize();

        $this->assertArrayHasKey('key', $serialized);
        $this->assertArrayHasKey('name', $serialized);
        $this->assertArrayHasKey('description', $serialized);
        $this->assertArrayHasKey('permissions', $serialized);
    }

    public function test_has_company_feature_will_always_return_false_when_company_is_not_enabled()
    {
        $this->assertFalse(Context::hasCompanyFeatures());
        $this->assertFalse(Context::userHasCompanyFeatures(new Fixtures\User));
        $this->assertFalse(Context::userHasCompanyFeatures(new Fixtures\Admin));
    }

    /**
     * @define-env defineHasCompanyEnvironment
     */
    public function test_has_company_feature_can_be_determined_when_company_is_enabled()
    {
        $this->assertTrue(Context::hasCompanyFeatures());
        $this->assertTrue(Context::userHasCompanyFeatures(new Fixtures\User));
        $this->assertFalse(Context::userHasCompanyFeatures(new Fixtures\Admin));
    }
}
