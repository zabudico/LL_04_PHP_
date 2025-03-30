document.addEventListener("DOMContentLoaded", () => {
  // Динамическое добавление шагов
  document.querySelectorAll("[data-add-step]").forEach((button) => {
    button.addEventListener("click", () => {
      const container = button.previousElementSibling;
      const input = document.createElement("input");
      input.type = "text";
      input.name = "steps[]";
      input.placeholder = "Введите шаг";
      input.classList.add("step-input");
      container.appendChild(input);
    });
  });

  // Подтверждение удаления задачи
  document.querySelectorAll("[data-delete-task]").forEach((button) => {
    button.addEventListener("click", (e) => {
      if (!confirm("Удалить задачу?")) {
        e.preventDefault();
      }
    });
  });
});
