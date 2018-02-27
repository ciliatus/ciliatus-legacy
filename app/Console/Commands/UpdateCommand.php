<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

class UpdateCommand extends Command
{

    protected function init_update($old_version, $new_version)
    {
        echo PHP_EOL . PHP_EOL .
            '                                   HH-   HH;
                             HHHHQ;?H7HHH7H?HH  HH;
                       ?QHHHQ:HHHHHHHH?77$H??7?HH77HO
                        :HHHHHHHHHHHHH7???7????77C?7HC
                   7HHHHHHHHHHHHHHH$777???$HHHHHO???7H
                     HHHHHHHHHHHHH7??????H.   HHHH???H?
                .HHHHHHHHH?OQHO??CHO7??CH     .HN HC?7H
                  HHHHHHH???OHHHH?CH7??HN  $ 7HHH NC?7H
               $HHHHHHHH????7HHHHH?HC??$H  HHHHH  HC??HO
              HHHHHHH?????????77???????7HN HHHH  N$C??7H;
             HHHHHH???????$??????????????7HHHHNHH?????C7H
            ?HHHHHH????HH$H?????????????????????????????7H:
            HHHHHH???$HC?????????????????QC???????C??????7HH
           HHHHHH???HQC??H????HC??????????HCC??CHO;QH????H7?QH$
           HHHHH???H??????C??77HQC??????????HQCH-O:-H????CHH?7HO
          .HHHHH??HC???OCCQ??77QHH?C??????????HH7H>HQC????$HH?HC
          7HHHHH?HC???CHCHC??77OH HHOCC?????????HHHHCCC???????H
          $HHHHHOHC????HHC???777H   HHHOCCC????????HHHHOC???HH
          OHHHHHQ?H$C??HQ????777$H      ?HHHHHQHHHCCCC????HH         $
          !HHHHHH??HH??7HHHHQC777H          7 HO7HHHHHOHH?          C>
           HHHHH$???HC?????HH?7777H       C$C$H??HHH?H             HH
           HHHHHH???OH?????HHHH7777H.$HHHHHHO????C??7H-           HHH
           $HHHHHH???HC????QHOHQ777OH7777?C???????H             CHHH$
            HHHHHH$??HC?C?CHO???777QH7?????????CC?HHHHH       QH7$HH
             HHHHHHCCH?H?H$7?H????7QH7????????CHHHH$?7H    OHH7??HH.
             HHHHHHH$H?QH?HHHQ?????$H7??????CQH.    ?H$HHHQ7???OHHH
              HHHHHHHHQHH??????????CH7???????CQHCCC???C???????HHHH
               HHHHHHC?????????????CH7???C?????CCHHC????????CHHHH
                CHHHHH???????????????7?CHHQ???????????????HHHHHO
                 .HHHHHH?????????????O??????????????????QHHHHH;
                   HHHHHHHHHHHHQ$O????????HHH?H???????$HHHHHH
                     HHHHHHHHHHHHHHHHQ????CH??CHHHQQHHHHHHH
                       HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH
                          HHHHHHHHHHHHHHHHHHHHHHHHHHHH
                              HHHHHHHHHHHHHHHHHHHH
                                    -7$QQ$7-'
            . PHP_EOL . PHP_EOL;
        echo "              #####  ### #       ###    #    ####### #     #  #####  
             #     #  #  #        #    # #      #    #     # #     # 
             #        #  #        #   #   #     #    #     # #       
             #        #  #        #  #     #    #    #     #  #####  
             #        #  #        #  #######    #    #     #       # 
             #     #  #  #        #  #     #    #    #     # #     # 
              #####  ### ####### ### #     #    #     #####   #####  " . PHP_EOL . PHP_EOL;
        echo "                                Ciliatus Updater" . PHP_EOL;
        echo "                               $old_version to $new_version" . PHP_EOL . PHP_EOL;

        sleep(2);
        echo "                               !!! ATTENTION !!!" . PHP_EOL;
        echo "            ATTENTION: Make sure you created a backup before continuing !!!" . PHP_EOL;
        echo "                               !!! ATTENTION !!!" . PHP_EOL . PHP_EOL;
        sleep(2);

        for ($i = 10; $i > 0; $i--) {
            echo "Update starts in $i seconds ..." . PHP_EOL;
            sleep(1);
        }

        echo "Starting upgrade to $new_version" . PHP_EOL;
    }

}
