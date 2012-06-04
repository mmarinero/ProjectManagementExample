#include <stddef.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <unistd.h>
#include <sys/wait.h>
int main(int argc, char **argv) {
    //update svn
    if (argc == 5  && 
        strcmp(argv[1], "update") == 0 && 
        strlen(argv[2]) < 100 && 
        strlen(argv[3]) < 100 && 
        strlen(argv[4]) < 100) {
        remove("application/config/config.php");
	remove(".htaccess");
        execl("/usr/bin/svn", "svn",
             "update", "--username",argv[3],
             "--password", argv[4],
             "--config-dir", argv[2],
             (const char *) NULL);
        fprintf(stderr, "Error al ejecutar svn update desde el ejecutable svnupdate\n");
        return(EXIT_FAILURE);
    }else if (argc == 6  &&
        strcmp(argv[1], "checkout") == 0 && 
        strlen(argv[2]) < 100 && 
        strlen(argv[3]) < 100 && 
        strlen(argv[4]) < 100 && 
        strlen(argv[5]) < 200) {
        int pid = fork();
        if (pid == 0) {
            execl("/usr/bin/find",".","-mindepth","1","-type","d","-exec","rm","-rf","{}","+",(const char *) NULL);
	} else {
            wait((int *) NULL);
            int pid2 = fork();
            if (pid2 == 0) {
                execl("/usr/bin/find",".","-type","f","!","-name","svnupdate*","-exec","rm","-f","{}","+",(const char *) NULL);
	    } else {
                wait((int *) NULL);
                execl("/usr/bin/svn", "svn", "checkout", argv[5], ".",
                     "--username",argv[3], "--password", argv[4], "--config-dir", argv[2], (const char *) NULL);
            }
        }
        fprintf(stderr, "Error al ejecutar svn checkout o rm desde el ejecutable svnupdate\n");
        return(EXIT_FAILURE);
    }else if (argc == 3  && strcmp(argv[1], "writefile") == 0) {
    //escribe fichero pasado por stdin si es uno de los posibles
 	char *filename;
        char buffer[32768];
        if (strcmp(argv[2], "config") == 0) {
            filename = "application/config/config.php";
	} else if (strcmp(argv[2], "htaccess") == 0) {
            filename = ".htaccess";
        } else {
            fprintf(stderr, "Fichero invalido\n");
            return(EXIT_FAILURE);
        }
        
        ssize_t nbytes;
        FILE *fp = fopen(filename, "w");

        if (fp == 0)
            error("No se pudo abrir el fichero\n");

        while ((nbytes = fread(buffer, sizeof(char), sizeof(buffer), stdin)) > 0)
            if (fwrite(buffer, sizeof(char), nbytes, fp) != nbytes)
                fprintf(stderr, "Error escribiendo fichero\n");
        fclose(fp);
    }else if (argc==3 && strcmp(argv[1],"chmod") == 0 && strlen(argv[2])<100) {
        char *filename;
        if (strcmp(argv[2], "logs") == 0) {
            filename = "application/logs";
	} else if (strcmp(argv[2], "templates_c") == 0) {
            filename = "application/views/templates_c";
        } else {
            fprintf(stderr, "Fichero invalido\n");
            return(EXIT_FAILURE);
        }
        execl("/bin/chmod","chmod","770",filename,(const char *) NULL);
        fprintf(stderr, "Error al ejecutar chmod\n");
        return(EXIT_FAILURE);
    } else {
        fprintf(stderr, "Parametros invalidos\n");
        return(EXIT_FAILURE);
    }
}
