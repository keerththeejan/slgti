<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Model;

final class PermissionService
{
    public static function forUser(int $userId, int $roleId): array
    {
        $rows = Model::fetchAll(
            'SELECT DISTINCT p.slug
             FROM permissions p
             INNER JOIN role_permissions rp ON rp.permission_id = p.id
             WHERE rp.role_id = ?
                OR rp.role_id IN (SELECT role_id FROM user_roles WHERE user_id = ?)',
            [$roleId, $userId]
        );
        return array_column($rows, 'slug');
    }
}
