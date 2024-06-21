<?php
declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\Pdf;

use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfReader\PdfReaderException;
use setasign\Fpdi\Tcpdf\Fpdi;

class PdfSignCertificateAdder
{
    private Fpdi $pdf;
    private ?string $error = null;

    /**
     * PdfSignCertificateAdder constructor.
     *
     * @param string $pdfFile
     *
     * @throws PdfParserException
     * @throws PdfReaderException
     * @throws \Exception
     */
    public function __construct(string $pdfFile)
    {
        if (is_file($pdfFile) === false) {
            throw new \Exception('Input file doesnt not exist: ' . $pdfFile);
        }
        if (is_readable($pdfFile) === false) {
            throw new \Exception('Input can not be open: ' . $pdfFile);
        }
        $this->pdf = new Fpdi();
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $pages = $this->pdf->setSourceFile($pdfFile);
        for ($i = 1; $i <= $pages; $i++) {
            $this->pdf->AddPage();
            $tplId = $this->pdf->importPage($i);
            $this->pdf->UseTemplate($tplId);
        }
    }

    /**
     * @param string $outputFile
     * @param string $certPath
     * @param string $password
     * @param array{Name: string, Location: string, Reason: string, ContactInfo: string} $info
     *
     * @return string|bool
     *
     * @throws \Exception
     */
    public function addCertificateToSignedPdf(string $outputFile, string $certPath, string $password, array $info): string|bool
    {
        $certificate = $certPath;
        $password1 = $password;

        // check signature exists
        if ( ! is_file($certificate)) {
            throw new \Exception('File with cert is missing: ' . $certificate);
        }
        try {
            // set document signature
            $this->pdf->setSignature('file://' . $certificate, 'file://' . $certificate, $password1, '', 1, $info);
        } catch (\Exception $e) {
            $this->error = 'Failed to sign PDF: ' . $e->getMessage();
            return false;
        }

        return $this->pdf->Output($outputFile, 'F');
    }

    /**
     * isError
     *
     * @return bool
     */
    public function isError(): bool
    {
        return ! empty($this->error);
    }

    /**
     * getError
     *
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}
