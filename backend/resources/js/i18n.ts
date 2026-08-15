import i18n from "i18next";
import { initReactI18next } from "react-i18next";

const resources = {
  en: {
    translation: {
      "Dashboard": "Dashboard",
      "Overview": "Overview",
      "Overdue": "Overdue",
      "Expiring Soon": "Expiring Soon",
      "Active": "Active",
      "Renewal in Progress": "Renewal in Progress",
      "What needs attention today?": "What needs attention today?",
      "Add Item": "Add Item",
      "Language": "Language",
      "Logout": "Logout",
      "Settings": "Settings"
    }
  },
  ms: {
    translation: {
      "Dashboard": "Papan Pemuka",
      "Overview": "Gambaran Keseluruhan",
      "Overdue": "Tunggakan",
      "Expiring Soon": "Hampir Tamat Tempoh",
      "Active": "Aktif",
      "Renewal in Progress": "Pembaharuan Sedang Diproses",
      "What needs attention today?": "Apa yang perlukan perhatian hari ini?",
      "Add Item": "Tambah Item",
      "Language": "Bahasa",
      "Logout": "Log Keluar",
      "Settings": "Tetapan"
    }
  }
};

i18n
  .use(initReactI18next)
  .init({
    resources,
    lng: "en",
    fallbackLng: "en",
    interpolation: {
      escapeValue: false
    }
  });

export default i18n;
