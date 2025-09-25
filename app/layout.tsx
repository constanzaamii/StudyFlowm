import type { Metadata } from 'next'
import '../public/css/globals.css'

export const metadata: Metadata = {
  title: 'StudyFlow',
  description: 'Aplicación de gestión de estudios',
  generator: 'Next.js',
}

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode
}>) {
  return (
    <html lang="es">
      <body className="font-inter antialiased">
        {children}
      </body>
    </html>
  )
}
