<?php
// controllers/ReportController.php
require_once 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

use Shuchkin\SimpleXLSXGen;


class ReportController {
    private $product;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->product = new Producto($this->db);
    }

    public function ReportePDF() {
        try {
            // Obtener productos
            $result = $this->product->obtenerProducto();
            $products = $result->fetchAll(PDO::FETCH_ASSOC);

            // Configurar DOMPDF
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);

            // Crear instancia de DOMPDF
            $dompdf = new Dompdf($options);

            // Preparar el HTML
            $html = $this->generatePDFTemplate($products);

            // Cargar HTML
            $dompdf->loadHtml($html);

            // Configurar papel y orientación
            $dompdf->setPaper('A4', 'portrait');

            // Renderizar PDF
            $dompdf->render();

            // Nombre del archivo
            $filename = 'productos_' . date('Y-m-d_H-i-s') . '.pdf';

            // Enviar al navegador
            $dompdf->stream($filename, array('Attachment' => true));

        } catch (Exception $e) {
            echo "Error al generar PDF: " . $e->getMessage();
        }
    }

    private function generatePDFTemplate($products) {
        $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Reporte de Productos</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f8f9fa;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                .header h1 {
                    color: #333;
                    margin: 0;
                    padding: 10px 0;
                }
                .footer {
                    text-align: center;
                    margin-top: 30px;
                    font-size: 10px;
                    color: #666;
                }
                .date {
                    text-align: right;
                    margin-bottom: 20px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Reporte de Productos</h1>
                <p>Sistema de Gestión de Inventario</p>
            </div>
            
            <div class="date">
                Fecha de generación: ' . date('d/m/Y H:i:s') . '
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($products as $product) {
            $html .= '
                <tr>
                    <td>' . $product['id_producto'] . '</td>
                    <td>' . htmlspecialchars($product['nombre']) . '</td>
                    <td>' . htmlspecialchars($product['descripcion']) . '</td>
                    <td>$' . number_format($product['precio'], 2) . '</td>
                    <td>' . $product['stock'] . '</td>
                </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="footer">
                <p>Este reporte fue generado automáticamente.</p>
                <p>Página 1 de 1</p>
            </div>
        </body>
        </html>';

        return $html;
    }

    public function ReporteExcel() {
        try {
            // Obtener productos
            $result = $this->product->obtenerProducto();
            $products = $result->fetchAll(PDO::FETCH_ASSOC);

            // Preparar los datos para el Excel
            $data = [];
            
            // Agregar encabezados
            $data[] = [
                'ID',
                'Nombre',
                'Descripción',
                'Precio',
                'Stock',
                'Fecha Creación',
                'Última Actualización'
            ];

            // Agregar datos
            foreach ($products as $product) {
                $data[] = [
                    $product['id_producto'],
                    $product['nombre'],
                    $product['descripcion'],
                    '$' . number_format($product['precio'], 2),
                    $product['stock'],
                    $product['fecha_creacion'],
                    $product['fecha_actualizacion']
                ];
            }

            // Agregar fila en blanco y totales
            $data[] = ['', '', '', '', '', '', '']; // Fila vacía
            $data[] = [
                'TOTALES',
                '',
                '',
                '$' . number_format(array_sum(array_column($products, 'precio')), 2),
                array_sum(array_column($products, 'stock')),
                '',
                ''
            ];

            // Generar el Excel
            $xlsx = SimpleXLSXGen::fromArray($data);

            // Establecer el nombre del archivo
            $filename = 'productos_' . date('Y-m-d_H-i-s') . '.xlsx';

            // Descargar el archivo
            $xlsx->downloadAs($filename);
            exit;

        } catch (Exception $e) {
            error_log("Error en exportación Excel: " . $e->getMessage());
            
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al generar el archivo Excel: ' . $e->getMessage()
            ]);
        }
    }
}