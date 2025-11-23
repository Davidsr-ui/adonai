import './bootstrap';
import React from "react";
import ReactDOM from "react-dom/client";
import Blog from "./components/Blog"; // Asegúrate de que exista resources/js/components/Blog.jsx

ReactDOM.createRoot(document.getElementById("blog-root")).render(
  <React.StrictMode>
    <Blog />
  </React.StrictMode>
);
