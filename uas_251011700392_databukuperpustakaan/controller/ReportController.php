<?php
// controller/ReportController.php

require_once __DIR__ . '/../libs/fpdf.php';

class ReportController {
    private $bukuModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit();
        }
        $this->bukuModel = new Buku();
    }

    public function index() {
        $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
        if ($kategori != '') {
            $data = $this->bukuModel->getByCategory($kategori);
        } else {
            $data = $this->bukuModel->getAll();
        }
        $kategoriList = $this->bukuModel->getAllCategories();
        include 'view/report/laporan.php';
    }

    // === GENERATE PDF (dengan MultiCell untuk Sinopsis) ===
    public function generatePDF() {
        $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
        if ($kategori != '') {
            $data = $this->bukuModel->getByCategory($kategori);
            $judul = "Laporan Buku - Kategori: $kategori";
        } else {
            $data = $this->bukuModel->getAll();
            $judul = "Laporan Semua Buku";
        }

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, $judul, 0, 1, 'C');
        $pdf->Ln(5);

        // Lebar kolom (total 275 mm, sisakan margin)
        $w = array(22, 40, 30, 30, 18, 25, 18, 30, 62); // ID, Judul, Pengarang, Penerbit, Tahun, ISBN, Halaman, Kategori, Sinopsis

        // Header
        $pdf->SetFont('Arial', 'B', 9);
        $header = array('ID', 'Judul', 'Pengarang', 'Penerbit', 'Tahun', 'ISBN', 'Halaman', 'Kategori', 'Sinopsis');
        for ($i = 0; $i < count($header); $i++) {
            $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
        }
        $pdf->Ln();

        // Data
        $pdf->SetFont('Arial', '', 8);
        foreach ($data as $b) {
            $x = $pdf->GetX();
            $y = $pdf->GetY();

            // Tulis kolom non-sinopsis
            $pdf->Cell($w[0], 6, $b['id'], 1, 0, 'C');
            $pdf->Cell($w[1], 6, $b['judul'], 1, 0, 'L');
            $pdf->Cell($w[2], 6, $b['pengarang'], 1, 0, 'L');
            $pdf->Cell($w[3], 6, $b['penerbit'], 1, 0, 'L');
            $pdf->Cell($w[4], 6, $b['tahun_terbit'], 1, 0, 'C');
            $pdf->Cell($w[5], 6, $b['isbn'], 1, 0, 'C');
            $pdf->Cell($w[6], 6, $b['jumlah_halaman'], 1, 0, 'C');
            $pdf->Cell($w[7], 6, $b['kategori'], 1, 0, 'L');

            // Sinopsis menggunakan MultiCell
            $pdf->SetXY($x + array_sum(array_slice($w, 0, 8)), $y);
            $pdf->MultiCell($w[8], 5, $b['sinopsis'], 1, 'L');

            // Set posisi Y ke bawah setelah MultiCell
            $y_new = $pdf->GetY();
            $pdf->SetXY($x, $y_new);
        }

        $pdf->Output('laporan_buku.pdf', 'D');
        exit();
    }

    // === GENERATE EXCEL (dengan CSS word-wrap) ===
    public function generateExcel() {
        $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
        if ($kategori != '') {
            $data = $this->bukuModel->getByCategory($kategori);
            $judul = "Laporan Buku - Kategori: $kategori";
        } else {
            $data = $this->bukuModel->getAll();
            $judul = "Laporan Semua Buku";
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="laporan_buku.xls"');

        echo '<html><head><meta charset="UTF-8">
        <style>
            table { border-collapse: collapse; width:100%; table-layout: fixed; }
            th, td { border:1px solid #000; padding:5px; word-wrap: break-word; }
            th { background-color:#f2f2f2; }
            .col-id { width:8%; }
            .col-judul { width:15%; }
            .col-pengarang { width:12%; }
            .col-penerbit { width:12%; }
            .col-tahun { width:7%; }
            .col-isbn { width:12%; }
            .col-halaman { width:8%; }
            .col-kategori { width:10%; }
            .col-sinopsis { width:20%; }
        </style>
        </head><body>';
        echo '<h2>'.$judul.'</h2>';
        echo '<table>
                <tr>
                    <th class="col-id">ID</th>
                    <th class="col-judul">Judul</th>
                    <th class="col-pengarang">Pengarang</th>
                    <th class="col-penerbit">Penerbit</th>
                    <th class="col-tahun">Tahun</th>
                    <th class="col-isbn">ISBN</th>
                    <th class="col-halaman">Halaman</th>
                    <th class="col-kategori">Kategori</th>
                    <th class="col-sinopsis">Sinopsis</th>
                </tr>';
        if (count($data) > 0) {
            foreach ($data as $b) {
                echo '<tr>';
                echo '<td>'.htmlspecialchars($b['id']).'</td>';
                echo '<td>'.htmlspecialchars($b['judul']).'</td>';
                echo '<td>'.htmlspecialchars($b['pengarang']).'</td>';
                echo '<td>'.htmlspecialchars($b['penerbit']).'</td>';
                echo '<td>'.$b['tahun_terbit'].'</td>';
                echo '<td>'.htmlspecialchars($b['isbn']).'</td>';
                echo '<td>'.$b['jumlah_halaman'].'</td>';
                echo '<td>'.htmlspecialchars($b['kategori']).'</td>';
                echo '<td>'.htmlspecialchars($b['sinopsis']).'</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="9">Tidak ada data</td></tr>';
        }
        echo '</table>';
        echo '<p>Dicetak: '.date('d-m-Y H:i:s').'</p>';
        echo '</body></html>';
        exit();
    }
}
?>