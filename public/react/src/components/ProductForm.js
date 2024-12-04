import React, { useState } from "react";

const ProductForm = ({ onAddProduct }) => {
  const [formVisible, setFormVisible] = useState(false);
  const [name, setName] = useState("");
  const [price, setPrice] = useState("");
  const [color, setColor] = useState("#ffffff");

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!name || !price || isNaN(price))
      return alert("Wypełnij poprawnie formularz!");
    onAddProduct({ id: Date.now(), name, price: parseFloat(price), color });
    setName("");
    setPrice("");
    setColor("#ffffff");
    setFormVisible(false);
  };

  return (
    <div>
      <button onClick={() => setFormVisible((prev) => !prev)}>
        {formVisible ? "Anuluj" : "Dodaj nowy produkt"}
      </button>
      {formVisible && (
        <form onSubmit={handleSubmit} style={{ marginTop: "10px" }}>
          <input
            type="text"
            placeholder="Nazwa"
            value={name}
            onChange={(e) => setName(e.target.value)}
          />
          <input
            type="number"
            placeholder="Cena"
            value={price}
            onChange={(e) => setPrice(e.target.value)}
          />
          <input
            type="color"
            value={color}
            onChange={(e) => setColor(e.target.value)}
          />
          <button type="submit">Dodaj</button>
        </form>
      )}
    </div>
  );
};

export default ProductForm;
