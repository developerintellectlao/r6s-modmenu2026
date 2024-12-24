/** @type {import('next').NextConfig} */
const nextConfig = {
    output: 'export',
  trailingSlash: true,
  reactStrictMode: false,
 
  env: {
    MAPBOX_TOKEN : process.env.MAPBOX_TOKEN
  },
};

export default nextConfig;
