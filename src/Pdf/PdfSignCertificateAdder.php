<?php
declare(strict_types=1);

namespace Bezpapirove\BezpapirovePhpLib\Pdf;

use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\Tcpdf\Fpdi;

class PdfSignCertificateAdder
{
    private Fpdi $pdf;
    private string $certificate;
    private string $password;
    private string $pages;
    private ?string $error = null;

    /**
     * PdfSignCertificateAdder constructor.
     *
     * @param string $pdfFile
     *
     * @throws PdfParserException
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
        $this->pages = $this->pdf->setSourceFile($pdfFile);
        for ($i = 1; $i <= $this->pages; $i++) {
            $this->pdf->AddPage();
            $tplId = $this->pdf->importPage($i);
            $this->pdf->UseTemplate($tplId);
        }
        return $this;
    }

    /**
     * @param string $outputFile
     * @param string $certPath
     * @param string $password
     * @param array $info
     *
     * @throws PdfParserException
     */
    public function addCertificateToSignedPdf(string $outputFile, string $certPath, string $password, array $info): string
    {

        $this->certificate = $certPath;
        $this->password = $password;

        // check signature exists
        if ( ! is_file($this->certificate)) {
            throw new \Exception('File with cert is missing: ' . $this->certificate);
        }
        try {
            // set document signature
            $this->pdf->setSignature('file://' . $this->certificate, 'file://' . $this->certificate, $this->password, '', 1, $info);
        } catch (\Exception $e) {
            $this->error = 'Failed to sign PDF: ' . $e->getMessage();
            return false;
        }

        return $this->pdf->Output($outputFile, 'F');
    }

    /**
     * isError
     *
     * @return string returns string meesage
     *
     * @throws throws description
     */
    public function isError(): bool
    {
        return ! empty($this->error);
    }

    /**
     * getError
     *
     * @return string returns string meesage
     *
     * @throws throws description
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}
