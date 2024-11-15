<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Caja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Caja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Caja query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Caja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Caja whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Caja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Caja whereUpdatedAt($value)
 */
	class Caja extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $url
 * @property string $title
 * @property string $description
 * @property int $priority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CarouselFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carousel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carousel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carousel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carousel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carousel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carousel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carousel wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carousel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carousel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Carousel whereUrl($value)
 */
	class Carousel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Producto> $productos
 * @property-read int|null $productos_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Categoria whereUpdatedAt($value)
 */
	class Categoria extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventario query()
 */
	class Inventario extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $numero_lote
 * @property string|null $fecha_vencimiento
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Producto|null $producto
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lote whereFechaVencimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lote whereNumeroLote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lote whereUpdatedAt($value)
 */
	class Lote extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodoDePago newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodoDePago newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodoDePago query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodoDePago whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodoDePago whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodoDePago whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MetodoDePago whereUpdatedAt($value)
 */
	class MetodoDePago extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $codigo
 * @property string $nombre
 * @property string|null $descripcion
 * @property string $unidad
 * @property string $numero_lote
 * @property string $fchVto
 * @property string $precio_costo
 * @property string $precio_venta
 * @property string $iva
 * @property string $utilidad
 * @property float $stock
 * @property int $categoria_id
 * @property int $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Categoria $categoria
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lote> $lotes
 * @property-read int|null $lotes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venta> $ventas
 * @property-read int|null $ventas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereFchVto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereNumeroLote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto wherePrecioCosto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto wherePrecioVenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereUnidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Producto whereUtilidad($value)
 */
	class Producto extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $usuario
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venta> $ventas
 * @property-read int|null $ventas_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $monto_total
 * @property int $metodo_pago_id
 * @property string $fecha_venta
 * @property int $vendedor_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MetodoDePago $metodoPago
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Producto> $productos
 * @property-read int|null $productos_count
 * @property-read \App\Models\User $vendedor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereFechaVenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereMetodoPagoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereMontoTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venta whereVendedorId($value)
 */
	class Venta extends \Eloquent {}
}

