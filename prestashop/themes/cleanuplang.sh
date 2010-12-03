for theme in shp_babylock shp_intersew shp_janome shp_ottlite shp_symagasinet; do
 (
  cd "$theme"
  for lang in da sv no; do
   find -name "$lang.php" |
    while read name; do
     echo "Fixing $theme/$name"
     stepsup="$(echo "$name" | sed -e "s+^\./++g" -e "s+[^/]*+..+g")"
     rm "$name";
     ln -s "$stepsup/shp_generic/$name" "$name"
    done
  done
 )
done
