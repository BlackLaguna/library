import React, { useState, useMemo } from "react";
import ProductTable from "./components/ProductTable";
import ProductForm from "./components/ProductForm";
import Summary from "./components/Summary";

const App = () => {
  const [products, setProducts] = useState([
    { id: 1, name: "Produkt 1", price: 10, color: "#f28b82", quantity: 0 },
    { id: 2, name: "Produkt 2", price: 20, color: "#fbbc04", quantity: 0 },
    { id: 3, name: "Produkt 3", price: 30, color: "#34a853", quantity: 0 },
    { id: 4, name: "Produkt 4", price: 40, color: "#4285f4", quantity: 0 },
    { id: 5, name: "Produkt 5", price: 50, color: "#9c27b0", quantity: 0 },
    { id: 6, name: "Produkt 6", price: 60, color: "#ff7043", quantity: 0 },
  ]);

  const [filterSelected, setFilterSelected] = useState(false);
  const [sortKey, setSortKey] = useState("id");

  const filteredProducts = useMemo(() => {
    const filtered = filterSelected
      ? products.filter((p) => p.quantity > 0)
      : products;
    return filtered.sort((a, b) =>
      typeof a[sortKey] === "number"
        ? a[sortKey] - b[sortKey]
        : a[sortKey].localeCompare(b[sortKey])
    );
  }, [products, filterSelected, sortKey]);

  const handleAddProduct = (newProduct) => {
    setProducts([...products, { ...newProduct, quantity: 0 }]);
  };

  const handleUpdateProduct = (id, change) => {
    setProducts((prev) =>
      prev.map((p) =>
        p.id === id ? { ...p, quantity: Math.max(0, p.quantity + change) } : p
      )
    );
  };

  const handleRemoveProduct = (id) => {
    setProducts((prev) => prev.filter((p) => p.id !== id));
  };

  return (
    <div style={{ padding: "20px" }}>
      <h1>Zarządzanie produktami</h1>
      <button onClick={() => setFilterSelected((prev) => !prev)}>
        {filterSelected ? "Pokaż wszystkie" : "Pokaż tylko wybrane"}
      </button>
      <ProductForm onAddProduct={handleAddProduct} />
      <ProductTable
        products={filteredProducts}
        onUpdateProduct={handleUpdateProduct}
        onRemoveProduct={handleRemoveProduct}
        setSortKey={setSortKey}
      />
      <Summary products={products} />
    </div>
  );
};

export default App;
