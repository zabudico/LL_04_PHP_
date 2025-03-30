//task-manager\public\js\script.js

document.addEventListener("DOMContentLoaded", () => {
  // Динамическое добавление шагов
  document.querySelectorAll("[data-add-step]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const container = btn
        .closest(".form-group")
        .querySelector("#steps-container");
      const input = document.createElement("input");
      input.type = "text";
      input.name = "steps[]";
      input.placeholder = "Введите шаг";
      input.classList.add("step-input");
      container.appendChild(input);
    });
  });

  // Подтверждение удаления
  document.querySelectorAll("[data-delete-task]").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      if (!confirm("Удалить задачу безвозвратно?")) e.preventDefault();
    });
  });

  // Анимация уведомлений
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => alert.remove(), 5000);
  });
});
