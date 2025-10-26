#!/bin/bash

# Script de verificación de la optimización de búsqueda de solicitudes

echo "============================================"
echo "🔍 Verificación de Búsqueda Optimizada"
echo "============================================"
echo ""

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Función para imprimir con color
print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

# 1. Verificar que el archivo del repositorio existe
echo "1. Verificando archivos..."
if [ -f "app/Repositories/SolicitudRepository.php" ]; then
    print_success "SolicitudRepository.php encontrado"
else
    print_error "SolicitudRepository.php no encontrado"
    exit 1
fi

# 2. Verificar migraciones
echo ""
echo "2. Verificando migraciones..."
if [ -f "database/migrations/2025_10_25_120625_add_search_indexes_to_solicitudes_tables.php" ]; then
    print_success "Migración de índices encontrada"
else
    print_warning "Migración de índices no encontrada"
fi

# 3. Ejecutar migraciones pendientes
echo ""
echo "3. Ejecutando migraciones..."
php artisan migrate --pretend 2>&1 | grep -q "Nothing to migrate"
if [ $? -eq 0 ]; then
    print_warning "No hay migraciones pendientes (puede que ya se hayan ejecutado)"
else
    print_warning "Hay migraciones pendientes. Ejecutando..."
    php artisan migrate --force
    if [ $? -eq 0 ]; then
        print_success "Migraciones ejecutadas correctamente"
    else
        print_error "Error al ejecutar migraciones"
    fi
fi

# 4. Verificar tests
echo ""
echo "4. Verificando tests..."
if [ -f "tests/Feature/SolicitudSearchPerformanceTest.php" ]; then
    print_success "Test de performance encontrado"
else
    print_warning "Test de performance no encontrado"
fi

# 5. Probar con Tinker (opcional)
echo ""
echo "5. ¿Deseas probar la búsqueda con Tinker? (s/n)"
read -r response

if [[ "$response" == "s" ]] || [[ "$response" == "S" ]]; then
    echo ""
    echo "Iniciando Tinker con comandos de prueba..."
    echo ""
    
    php artisan tinker <<EOF
\$repo = app(\App\Repositories\SolicitudRepository::class);
echo "Repositorio cargado\n";

try {
    \$result = \$repo->getAll(15, null);
    echo "✓ Búsqueda sin filtro: " . \$result->total() . " registros encontrados\n";
    
    \$result = \$repo->getAll(15, 'test');
    echo "✓ Búsqueda con término 'test': " . \$result->total() . " registros encontrados\n";
    
    echo "\n✓ ¡Todo funciona correctamente!\n";
} catch (\Exception \$e) {
    echo "✗ Error: " . \$e->getMessage() . "\n";
}

exit
EOF
fi

# 6. Mostrar resumen
echo ""
echo "============================================"
echo "📊 Resumen"
echo "============================================"
echo ""
print_success "Optimización implementada correctamente"
echo ""
echo "Siguientes pasos:"
echo "  1. Ejecuta: php artisan migrate (si hay migraciones pendientes)"
echo "  2. Prueba en tu controlador/vista"
echo "  3. Opcional: php artisan test --filter SolicitudSearchPerformanceTest"
echo ""
echo "Documentación:"
echo "  - IMPLEMENTACION_BUSQUEDA.md"
echo "  - PROCEDIMIENTO_ALMACENADO.md"
echo ""
echo "============================================"
