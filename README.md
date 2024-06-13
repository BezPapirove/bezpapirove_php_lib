# BezPapirove PHP Library

Common library for **BezPapirove s.r.o.** which provide classes useable in PHP projects.  

Library is fully covered by unit tests.


### List of some important functions 

- file handler
    - resolve correct file path in storage
        - *FolderStructure::getFolderStructureFromFileName($filename) : array*
        - *FolderStructure::pathExists(string $base_path, array $path_list) : bool*
        - *FolderStructure::createFolderStructure(string $base_path, array $path_list) : bool*

    - handling file in data storage
        - *Handler->download(string $file_name)*
        - *Handler->upload(string $file_path) : string*
        - *Handler->exists(string $file_name) : bool*
    - generate file UUID name
- db connector

