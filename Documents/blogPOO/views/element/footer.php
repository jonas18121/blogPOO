            </section>
        </main>
        <footer>
            <div>
                <?php if(defined('DEBUG_TIME')) : ?>
                    <p>page généré en <?= round(1000 * microtime(true) - DEBUG_TIME) ?> millisecondes</p>
                <?php endif ?>
            </div>
        </footer>
        <script type="text/javascript" src="/js/main.js"></script>
    </body>
</html>