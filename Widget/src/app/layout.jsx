import localFont from "next/font/local";
import "./globals.css";
import Layout from '@/features/layout';

// const geistSans = localFont({
//   src: "./fonts/GeistVF.woff",
//   variable: "--font-geist-sans",
//   weight: "100 900",
// });
// const geistMono = localFont({
//   src: "./fonts/GeistMonoVF.woff",
//   variable: "--font-geist-mono",
//   weight: "100 900",
// });



// const Roboto = localFont({
//   src: "./fonts/Roboto Black.ttf",
//   variable: "--font-roboto",
//   weight: "100 900",
// });

export const metadata = {
  title: "MRC",
  description: "MRC Mekong river monitoring and forecasting",
};

export default function RootLayout({ children }) {
  return (
    <html lang="en">
      <body >
        <Layout>
          {children}
        </Layout>
      </body>
    </html>
  );
}
