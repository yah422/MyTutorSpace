# 🎓 MyTutorSpace - Plateforme de Tutorat Connecté

![Bannière MyTutorSpace](https://github.com/user-attachments/assets/1f595fc5-0969-45be-b2b0-ae8641e4b1e0)

## 🌟 Transformez votre apprentissage

MyTutorSpace révolutionne le soutien scolaire en connectant étudiants et tuteurs experts à travers une expérience digitale fluide et personnalisée.

### 🚀 Fonctionnalités clés
- **Catalogue de cours** - Parcourir des centaines de ressources pédagogiques triées par matière et niveau
- **Espace exercices** - Mettez en pratique vos connaissances avec des exercices corrigés
- **Matching intelligent** - Trouvez le tuteur idéal grâce à notre algorithme de recommandation
- **Classes virtuelles** - Bénéficiez de cours particuliers en visioconférence

## 💻 Stack Technique

<div style="display: flex; gap: 15px; flex-wrap: wrap; margin: 20px 0;">
    <img src="https://github.com/user-attachments/assets/3b8add42-9228-459f-b2e7-341bc45d2d3e" alt="Symfony" width="50">
    <img src="https://github.com/user-attachments/assets/6aeaeefc-e510-4f19-9b60-78f3e7b7e2ca" alt="PHP" width="50">
    <img src="https://github.com/user-attachments/assets/38794bac-1b9d-4884-a8f6-ca497ed1ae21" alt="Tailwind CSS" width="50">
</div>

**Backend**  
- Symfony 6.3 avec API Platform  
- Doctrine ORM pour la gestion des données  
- Système d'authentification JWT  

**Frontend**  
- Tailwind CSS 3.0 avec design responsive  

## 🛠 Mise en route

1. **Cloner le projet**
```bash
git clone https://github.com/yah422/MyTutorSpace.git && cd MyTutorSpace
```

2. **Installer les dépendances**
```bash
composer install && npm install
```

3. **Configurer l'environnement**  
Créez un fichier `.env.local` et configurez :
```ini
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/mytutorspace?serverVersion=8.0"
```

4. **Initialiser la base de données**
```bash
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

5. **Lancer l'application**
```bash
symfony server:start
npm run dev
```

## 📱 Aperçu de l'interface

<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin: 25px 0;">
<img width="200" height="600" alt="image" style="border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);" src="https://github.com/user-attachments/assets/f757d782-8091-49ff-85f5-973ae75a3c02" />
<img width="200" height="600" style="border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);" alt="image" src="https://github.com/user-attachments/assets/2d5efd13-2e3a-41aa-8850-61ae3420337c" />
<img width="200" height="600" style="border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);" alt="image" src="https://github.com/user-attachments/assets/3e9b31dc-3fbd-4e3f-9933-a134242f40e0" />

</div>

## 📅 Roadmap 2025
- [ ] Intégration de l'IA pour l'analyse des difficultés des élèves
- [ ] Application mobile React Native
- [ ] Système de badges et récompenses
- [ ] Tableau de bord analytique pour les tuteurs

## 👩💻 Auteure
**Asma SAIDI**  
Développeuse Full-Stack passionnée par l'éducation digitale  

[![GitHub](https://img.shields.io/badge/GitHub-Profile-%23181717?logo=github)](https://github.com/yah422)  
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Connect-%230A66C2?logo=linkedin)](https://linkedin.com/in/asma-saïdi-698b07297)

---

✨ **Fait avec passion pour l'éducation de demain** ✨
