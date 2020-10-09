# TODO.md

### Catàleg

- [ ] Que es vegi un enllaç del catàleg de IPC + una frase introductoria 
- [ ] Mostrar **File Name** dels arxius a la taula (info extreta del Nextcloud)
    - [ ]  Preparar PHP endpoint que donada una URN retorni un Nextcloud FilePath
    - [ ]  Preparar nova columna 'filename ID'
- [ ] Mostrar el **Storage** del file (o servidor "data.ipc-project" o tipus d'storage "Nextcloud")
  - [ ] Analitzar el diferents 'file locators' que existeixen al catàleg
  - [ ] Preparar columna amb l'Storage parsejant el 'File Locator'
  - [ ] Només si 'File Locator' == "nc:..." --> habilitar botó IMPORT
- [ ] Control d'errors:

### Shiny

- [ ] Log (ajuntar els diferents logs en un o esborrar cada dia els logs dels dies anteriors)
- [ ] Passar els fitxers necessaris per a cada visualització utilitzant un endpoint 

### new Tools
- [ ] Carnival (w/Rosa)
    - [ ] define I/O
    - [ ] wrapper
    - [ ] tool specification
    - [ ] new VM
    - [ ] new SGE queue 
- [ ]  Drug prediction From Expression Profile (w/Mario)
    - [ ] define I/O
    - [ ] wrapper
    - [ ] tool specification
    - [ ] new VM
    - [ ] new SGE queue 
