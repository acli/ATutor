for t in tests/*; do
    flags=
    case "$t" in
    *_gpc.t.php)
        flags="$flags -d magic_quotes_gpc=on"
        ;;
    *_nogpc.t.php)
        flags="$flags -d magic_quotes_gpc=off"
        ;;
    esac
    echo "Running: php $flags \"$t\"" || break
    php $flags "$t" || break
done
