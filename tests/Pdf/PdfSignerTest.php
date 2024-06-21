<?php
declare(strict_types=1);

namespace Tests\Pdf;

use Bezpapirove\BezpapirovePhpLib\Pdf\PdfSignCertificateAdder;
use PHPUnit\Framework\TestCase;
use setasign\Fpdi\PdfParser\PdfParserException;

final class PdfSignerTest extends TestCase
{

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @throws PdfParserException
     */
    public function testPdfSignCertificateAdder(): void
    {
        $inputPdfPath = __DIR__ . '/sample.pdf';
        $outputPdfPath = __DIR__ . '/signed_sample.pdf';
        $certificate =  __DIR__ . '/../../src/Pdf/tcpdf.crt';
        $password = 'qawsed';

        // Assert if files exist and is readable
        $this->assertFileExists($inputPdfPath);
        $this->assertFileExists($certificate);
        $this->assertFileIsReadable($inputPdfPath);
        $this->assertFileIsReadable($certificate);

        // Instantiate the PdfSignCertificateAdder
        $pdfSigner = new PdfSignCertificateAdder($inputPdfPath);

        $info = [
            'Name' => 'BezPapirove',
            'Location' => 'Brno',
            'Reason' => 'Testing',
            'ContactInfo' => 'https://www.bezpapirove.cz',
        ];

        $result = $pdfSigner->addCertificateToSignedPdf($outputPdfPath, $certificate, $password, $info);
        if ($result === false) {
            $this->assertTrue($pdfSigner->isError());
            $this->assertNotEmpty($pdfSigner->getError());
        } else {
            $this->assertFileExists($outputPdfPath);
            $this->assertEmpty($pdfSigner->getError());
            $this->assertFalse($pdfSigner->isError());
        }

        $content = file_get_contents($outputPdfPath);
        $this->assertNotEmpty($content);
    }

}
