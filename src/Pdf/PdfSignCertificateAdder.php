<?php

namespace Bezpapirove\BezpapirovePhpLib\Pdf;

use setasign\Fpdi\Tcpdf;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\Tcpdf\Fpdi;

class PdfSignCertificateAdder
{
	private Fpdi $pdf;
	private string $privateKeyPath;
	private string $certificate;
	private string $password;

	const HASH_ALGO = 'sha256';
	const SIGN_FIELD_NAME = 'signature_field_name';
	const SIGN_ALGO = OPENSSL_ALGO_SHA256;

	/**
	 * PdfSignCertificateAdder constructor.
	 *
	 * @param string $pdfFile
	 * @param string $privateKeyPath
	 * @param string $certificate
	 * @param string $password
	 *
	 * @throws PdfParserException
	 */
	public function __construct(string $pdfFile, string $privateKeyPath, string $certificate, string $password)
	{
		$this->pdf = new Fpdi();
		$this->privateKeyPath = $privateKeyPath;
		$this->certificate = $certificate;
		$this->password = $password;
		$this->pdf->setSourceFile($pdfFile);
	}


	/**
	 * @throws PdfParserException
	 */
	public function addCertificateToSignedPdf(string $signedPdf, string $certPath, string $hash): void
	{
		// Step 1: Get the private key
		$privateKey = $this->getPrivateKey();
		if ($privateKey === false) {
			throw new \Exception("Failed to retrieve Private Key");
		}

		// Step 2: Sign the hash
		openssl_sign($hash, $signature, $privateKey, self::SIGN_ALGO);
		unset($privateKey);

		// Step 3: Load the certificate
		$certificate = file_get_contents($certPath);
		if ($certificate === false) {
			throw new \Exception("Error loading Certificate");
		}

		// Step 4: Load the signed PDF
		$oldPdf = $this->pdf;
		$this->pdf = new Fpdi();
		$this->pdf->setSourceFile($signedPdf);

		// Step 5: Add the signature and the certificate
		$this->pdf->setSignature($signature, $certificate, $this->password);

		// Step 6: Resave the signed PDF with the added certificate
		$this->pdf->Output($signedPdf, 'F');

		// Step 7: Restore the old PDF instance
		$this->pdf = $oldPdf;
	}

	private function getPrivateKey(): \OpenSSLAsymmetricKey
	{
		$privateKey = openssl_pkey_get_private(file_get_contents($this->privateKeyPath));
		if ($privateKey === false) {
			throw new \Exception("Failed to retrieve Private Key");
		}
		return $privateKey;
	}

}

//// Example usage:
//$pdfFile = 'path/to/your/input.pdf';
//$signatureImage = 'path/to/your/signature.png';
//$outputFile = 'path/to/your/signed_output.pdf';
//$privateKeyPath = 'path/to/your/private-key.pem';
//$certificate = 'path/to/your/certificate.pem';
//$password = 'your_certificate_password';
//
//	$signer = new PdfSignCertificateAdder(
//		$pdfFile,
//		$signatureImage,
//		$outputFile,
//		$privateKeyPath
//	);
//$hash = hash_file('sha256', $signedPdf);
//$signer->addCertificateToSignedPdf($signedPdf, $certPath, $hash);

