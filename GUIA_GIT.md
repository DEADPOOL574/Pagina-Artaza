# Guía para Subir el Proyecto a GitHub

## Paso 1: Crear un repositorio en GitHub

1. Ve a [GitHub](https://github.com) y crea una cuenta (si no tienes una)
2. Haz clic en el botón "+" en la esquina superior derecha
3. Selecciona "New repository"
4. Nombra tu repositorio (ej: `respawn-news`)
5. Elige si será público o privado
6. **NO** marques "Initialize this repository with a README" (ya tenemos uno)
7. Haz clic en "Create repository"

## Paso 2: Inicializar Git en tu proyecto

Abre PowerShell o CMD en la carpeta del proyecto y ejecuta:

```bash
# Inicializar el repositorio Git
git init

# Agregar todos los archivos
git add .

# Hacer el primer commit
git commit -m "Initial commit: Sistema Respawn News completo"
```

## Paso 3: Conectar con GitHub

```bash
# Agregar el repositorio remoto (reemplaza TU_USUARIO y TU_REPOSITORIO)
git remote add origin https://github.com/TU_USUARIO/TU_REPOSITORIO.git

# Verificar que se agregó correctamente
git remote -v
```

## Paso 4: Subir el código

```bash
# Subir el código a GitHub (primera vez)
git branch -M main
git push -u origin main
```

## Comandos útiles para futuros cambios

```bash
# Ver el estado de los archivos
git status

# Agregar archivos modificados
git add .

# O agregar archivos específicos
git add nombre_archivo.php

# Hacer commit de los cambios
git commit -m "Descripción de los cambios"

# Subir los cambios a GitHub
git push
```

## Notas importantes

⚠️ **Antes de subir, verifica que el archivo `.gitignore` esté configurado correctamente**
- No subas archivos con contraseñas o datos sensibles
- El archivo `config/db.php` está en `.gitignore` por seguridad
- Crea un archivo `config/db.example.php` como plantilla

## Solución de problemas

### Si te pide usuario y contraseña:
GitHub ya no acepta contraseñas, usa un **Personal Access Token**:
1. Ve a GitHub → Settings → Developer settings → Personal access tokens
2. Genera un nuevo token con permisos `repo`
3. Usa el token como contraseña cuando te lo pida

### Si hay conflictos:
```bash
# Descargar cambios del repositorio
git pull origin main

# Resolver conflictos manualmente y luego:
git add .
git commit -m "Resuelto conflicto"
git push
```

