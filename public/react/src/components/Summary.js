import React from "react";

const Summary = ({ products }) => {
  const totalItems = products.reduce((sum, p) => sum + p.quantity, 0);
  const totalPrice = products.reduce((sum, p) => sum + p.quantity * p.price, 0);
  const selectedProducts = products.filter((p) => p.quantity > 0);

  return (
    <div style={{ marginTop: "20px" }}>
      <h3>Podsumowanie</h3>
      <p>Łączna ilość produktów: {totalItems}</p>
      <p>Łączna cena: {totalPrice} zł</p>
      <p>
        Wybrane produkty:{" "}
        {selectedProducts.length > 0
          ? selectedProducts.map((p) => p.name).join(", ")
          : "brak"}
      </p>
    </div>
  );
};

export default Summary;
