import React from "react";

const ProductTable = ({
  products,
  onUpdateProduct,
  onRemoveProduct,
  setSortKey,
}) => {
  return (
    <div>
      <table border="1" style={{ width: "100%", marginTop: "20px" }}>
        <thead>
          <tr>
            <th onClick={() => setSortKey("id")}>ID</th>
            <th onClick={() => setSortKey("name")}>Nazwa</th>
            <th onClick={() => setSortKey("price")}>Cena</th>
            <th>Kolor</th>
            <th>Ilość</th>
            <th>Akcje</th>
          </tr>
        </thead>
        <tbody>
          {products.map((product) => (
            <tr key={product.id} style={{ backgroundColor: product.color }}>
              <td>{product.id}</td>
              <td>{product.name}</td>
              <td>{product.price} zł</td>
              <td>{product.color}</td>
              <td>{product.quantity || "brak"}</td>
              <td>
                <button onClick={() => onUpdateProduct(product.id, 1)}>
                  +
                </button>
                <button onClick={() => onUpdateProduct(product.id, -1)}>
                  -
                </button>
                <button
                  onClick={() => onUpdateProduct(product.id, -product.quantity)}
                >
                  0
                </button>
                <button onClick={() => onRemoveProduct(product.id)}>
                  Usuń
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default ProductTable;
