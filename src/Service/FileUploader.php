<?php

namespace App\Service;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $slugger;
    private $filesystem;

    private $enviro;

    private $tempDir;

    public function __construct(FilesystemInterface $filesystem, SluggerInterface $slugger)
    {
        $this->filesystem = $filesystem;
        $this->slugger = $slugger;
        $this->enviro = $_ENV['APP_ENV'] === 'prod' ? 'prod/' : 'test/';
        $this->tempDir = '/tmp';
    }

    public function upload(UploadedFile $file, $nombre, $path = false): string
    {
        $safeFilename =  $this->slugger->slug($nombre);
        $newFilename =  $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        if ($path) {
            $path = '/' . $this->enviro . $path . '/' .  $newFilename;
        } else {
            $path = '/' . $this->enviro .  $newFilename;
        }
        $stream = fopen($file->getPathname(), 'r');
        $result = $this->filesystem->writeStream($path, $stream, ['ACL' => 'public-read']);
        if ($result === false) {
            throw new FileException(
                sprintf('Could not write uploaded file %s', $newFilename)
            );
        }
        if (is_resource($stream)) {
            fclose($stream);
        }
        return $path;
    }

    public function uploadPdf($pdfContent, $nombre, $path = null): string
    {
        $safeFilename = pathinfo($nombre, PATHINFO_FILENAME);
        $newFilename = $safeFilename . '-' . uniqid() . '.pdf';

        // Ruta temporal del archivo HTML
        $tempHtmlFile = $this->tempDir . '/' . uniqid('knp_snappy') . '.html';
        file_put_contents($tempHtmlFile, $pdfContent);

        // Ruta temporal del archivo PDF
        $tempPdfFile = $this->tempDir . '/' . uniqid('knp_snappy') . '.pdf';

        // Determinar la ruta del ejecutable wkhtmltopdf
        $wkhtmltopdfCommand = '/usr/local/bin/wkhtmltopdf'; // Ruta por defecto en Linux

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Si el sistema operativo es Windows
            $wkhtmltopdfCommand = '"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe"';
        }

        // Generar PDF utilizando wkhtmltopdf
        exec("$wkhtmltopdfCommand --lowquality $tempHtmlFile $tempPdfFile");

        // Guardar PDF en el sistema de archivos
        if ($path) {
            $path = '/' . $this->enviro . $path . '/' .  $newFilename;
        } else {
            $path = '/' . $this->enviro .  $newFilename;
        }
        $result = $this->filesystem->write($path, file_get_contents($tempPdfFile), ['ACL' => 'public-read']);

        // Eliminar archivos temporales
        unlink($tempHtmlFile);
        unlink($tempPdfFile);

        if ($result === false) {
            throw new FileException(sprintf('Could not write PDF file %s', $newFilename));
        }

        return $path;
    }
}
