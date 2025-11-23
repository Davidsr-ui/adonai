import React from "react";
import Navbar from "./Navbar";
import BlogEscolar from "./Blog";
import Footer from "./Footer";

export default function BlogPage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navbar />
      <main className="flex-grow">
        <BlogEscolar />
      </main>
      <Footer />
    </div>
  );
}